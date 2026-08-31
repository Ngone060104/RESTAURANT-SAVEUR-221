<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function register(): void
    {
        try {
            $this->authService->register($_POST);
            $this->redirect('/login');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function login(): void
    {
        try {
            $email = $_POST['email'] ?? '';
            $mdp = $_POST['mdp'] ?? '';

            $user = $this->authService->login($email, $mdp);

            $this->redirectAfterLogin($user['role']);
        } catch (AuthException $e) {
            http_response_code(401);
            echo $e->getMessage();
        }
    }

    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }

    private function redirectAfterLogin(string $role): void
    {
        $destination = match ($role) {
            'ADMIN' => '/admin/dashboard',
            'GERANT' => '/gerant/dashboard',
            default => '/',
        };

        $this->redirect($destination);
    }
}
