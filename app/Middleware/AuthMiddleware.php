<?php

namespace App\Middleware;

use App\Interfaces\MiddlewareInterface;
use App\Services\AuthService;

/**
 * Bloque l'accès à une route si aucun utilisateur n'est connecté,
 * quel que soit son rôle. À combiner avec ClientMiddleware /
 * GerantMiddleware / AdminMiddleware pour restreindre par rôle.
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        if (AuthService::currentUser() === null) {
            header('Location: /login');

            return false;
        }

        return true;
    }
}
