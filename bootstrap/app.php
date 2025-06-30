<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware para rutas API
        $middleware->group('api', [
            \Illuminate\Http\Middleware\HandleCors::class, // Middleware CORS incorporado
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Middleware para rutas web (si es necesario)
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de excepciones para API
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $statusCode = $this->determineStatusCode($e);

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e instanceof ValidationException ? $e->errors() : null,
                    // Ocultar detalles de archivo y línea en producción
                    'debug' => config('app.debug') ? [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTrace()
                    ] : null,
                ], $statusCode);
            }
        });

        // Opcional: Reporte de excepciones
        $exceptions->report(function (Throwable $e) {
            // Lógica personalizada para reportar errores
        });
    })
    ->create();

// Función auxiliar para determinar el código de estado
if (!function_exists('determineStatusCode')) {
    function determineStatusCode(Throwable $e): int
    {
        if (method_exists($e, 'getStatusCode')) {
            return $e->getStatusCode();
        }

        if (method_exists($e, 'getCode') && $e->getCode() !== 0) {
            $code = $e->getCode();
            return ($code >= 100 && $code < 600) ? $code : 500;
        }

        return 500;
    }
}
