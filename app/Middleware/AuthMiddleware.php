<?php

namespace App\Middleware;

use App\Exceptions\AuthException;
use App\Interfaces\MiddlewareInterface;
use App\Services\AuthService;

/**
 * Bloque l'accès à une route si aucun utilisateur n'est connecté,
 * quel que soit son rôle.
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (AuthService::currentUser() === null) {
            throw new AuthException(
                'Vous devez être connecté pour accéder à cette page.'
            );
        }

        return true;
    }
}