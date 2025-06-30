<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allowed_origins' => [
        'https://front-decameron.vercel.app',
        'http://localhost:3000' // Para desarrollo local
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept'
    ],
    'exposed_headers' => [],
    'max_age' => 86400,
    'supports_credentials' => false,
];