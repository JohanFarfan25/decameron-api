<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    'allowed_origins' => ['https://front-decameron.vercel.app'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 86400, // ← Cache de 24 horas
    'supports_credentials' => false,
];