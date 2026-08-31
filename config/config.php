<?php

use App\Core\Env;

Env::load(__DIR__ . '/../.env');

return [
    'db' => [
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => Env::get('DB_PORT', '5432'),
        'name' => Env::get('DB_NAME', 'saveur221'),
        'user' => Env::get('DB_USER', 'postgres'),
        'pass' => Env::get('DB_PASS', 'admin'),
    ],
    'app' => [
        'url' => Env::get('APP_URL', 'http://localhost:8000'),
    ],
];
