<?php

namespace App\Middleware;

use App\Exceptions\AuthException;
use App\Exceptions\ForbiddenException;
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

        // Non connecté → 401
        if ($user === null) {
            throw new AuthException(
                'Vous devez être connecté pour accéder à cette page.'
            );
        }

        // Connecté mais mauvais rôle → 403
        if ($user['role'] !== 'ADMIN') {
            throw new ForbiddenException(
                "Cette page est réservée à l'administrateur."
            );
        }

        return true;
    }
}