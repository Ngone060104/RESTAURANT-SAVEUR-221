<?php

namespace App\Middleware;

use App\Core\View;
use App\Interfaces\MiddlewareInterface;
use App\Services\AuthService;

class ClientMiddleware implements MiddlewareInterface
{
    public function handle(): bool
    {
        $user = AuthService::currentUser();

      if ($user === null) {
    $_SESSION['redirect_after_login'] = '/panier';
    header('Location: /login');
    return false;
}

        if ($user['role'] !== 'CLIENT') {
            http_response_code(403);
            View::render('errors/403', [
                'message' => 'Cette page est réservée aux clients.'
            ]);
            return false;
        }

        return true;
    }
}