<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:9000',
        'http://127.0.0.1:9000',
        'http://localhost:9001',
        'http://127.0.0.1:9001',
        'http://localhost:9002',
        'http://127.0.0.1:9002',
        'http://localhost:9003',
        'http://127.0.0.1:9003',
        'http://localhost:9004',
        'http://127.0.0.1:9004',
    ],
    'allowed_headers' => ['*'],
];
