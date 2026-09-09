<?php

namespace App\Middleware;

use App\Exceptions\AuthException;
use App\Exceptions\ForbiddenException;
use App\Interfaces\MiddlewareInterface;
use App\Services\AuthService;

class ClientMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        $user = AuthService::currentUser();

        // Utilisateur non connecté → 401
        if ($user === null) {
            $_SESSION['redirect_after_login'] = '/panier';

            throw new AuthException(
                'Vous devez être connecté pour accéder à cette page.'
            );
        }

        // Utilisateur connecté mais mauvais rôle → 403
        if ($user['role'] !== 'CLIENT') {
            throw new ForbiddenException(
                'Cette page est réservée aux clients.'
            );
        }

        return true;
    }
}