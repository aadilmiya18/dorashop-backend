<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:9000',
        'http://127.0.0.1:9000',
        'http://localhost:9001',
        'http://127.0.0.1:9001',
    ],
    'allowed_headers' => ['*'],
];
