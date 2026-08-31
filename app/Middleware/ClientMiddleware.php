<?php

namespace App\Middleware;

use App\Interfaces\MiddlewareInterface;
use App\Services\AuthService;

/**
 * Réserve la route au rôle CLIENT (panier, commandes, profil, avis).
 */
class ClientMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        $user = AuthService::currentUser();

        if ($user === null) {
            header('Location: /login');

            return false;
        }

        if ($user['role'] !== 'CLIENT') {
            http_response_code(403);
            echo 'Accès réservé aux clients.';

            return false;
        }

        return true;
    }
}
