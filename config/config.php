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
    //  AJOUTEZ CECI POUR CLOUDINARY
    'cloudinary' => [
        'cloud_name' => Env::get('CLOUDINARY_CLOUD_NAME'),
        'api_key' => Env::get('CLOUDINARY_API_KEY'),
        'api_secret' => Env::get('CLOUDINARY_API_SECRET'),
        'upload_preset' => Env::get('CLOUDINARY_UPLOAD_PRESET'),
        'folder' => Env::get('CLOUDINARY_FOLDER', 'restaurant_saveur_221/produits'),
    ],
];
