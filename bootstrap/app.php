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
        // Middleware para rutas web
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Middleware para rutas API con CORS
        $middleware->group('api', [
            \Illuminate\Http\Middleware\HandleCors::class, // Middleware CORS incorporado
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Configuración global de CORS (opcional)
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de excepciones para API
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $statusCode = method_exists($e, 'getStatusCode') 
                    ? $e->getStatusCode() 
                    : (method_exists($e, 'getCode') && $e->getCode() !== 0 
                        ? $e->getCode() 
                        : 500);

                // Validar que el código de estado sea un valor HTTP válido
                $statusCode = $statusCode >= 100 && $statusCode < 600 ? $statusCode : 500;

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e instanceof ValidationException ? $e->errors() : null,
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ], $statusCode);
            }
        });
    })
    ->create();