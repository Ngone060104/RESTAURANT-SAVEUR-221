<?php

namespace App\Middleware;

use App\Interfaces\MiddlewareInterface;
use App\Services\AuthService;

/**
 * Règle métier n°13 : l'espace administrateur est réservé au rôle ADMIN.
 */
class AdminMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        $user = AuthService::currentUser();

        if ($user === null) {
            header('Location: /login');

            return false;
        }

        if ($user['role'] !== 'ADMIN') {
            http_response_code(403);
            echo 'Accès réservé à l\'administrateur.';

            return false;
        }

        return true;
    }
}
