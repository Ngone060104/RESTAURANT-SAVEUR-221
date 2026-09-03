<?php

namespace App\Middleware;

use App\Core\View;
use App\Interfaces\MiddlewareInterface;
use App\Services\AuthService;

/**
 * Règle métier n°14 : l'espace gérant est réservé au GERANT et à l'ADMIN
 * (l'admin a tous les droits du gérant).
 */
class GerantMiddleware implements MiddlewareInterface
{
    private const ROLES_AUTORISES = ['GERANT', 'ADMIN'];

    public function handle(): bool
    {
        $user = AuthService::currentUser();

        if ($user === null) {
            header('Location: /login');

            return false;
        }

        if (!in_array($user['role'], self::ROLES_AUTORISES, true)) {
            http_response_code(403);
            View::render('errors/403', [
                'message' => "Cette page est réservée au gérant et à l'administrateur.",
            ], null);

            return false;
        }

        return true;
    }
}
