<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Affiche la page de connexion.
     */
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'erreur' => null,
        ]);
    }

    /**
     * Affiche la page d'inscription.
     */
    public function showRegister(): void
    {
        $this->view('auth/register', [
            'erreurs' => [],
        ]);
    }

    /**
     * Traite l'inscription.
     */
    public function register(): void
    {
        try {
            $this->authService->register($_POST);

            $this->redirect('/login');
        } catch (ValidationException $e) {

            http_response_code(422);

            $this->view('auth/register', [
                'erreurs' => $e->getErrors(),
            ]);
        }
    }

    /**
     * Traite la connexion.
     */
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $mdp = $_POST['mdp'] ?? '';

        $erreurs = [];

        // Validation du champ email
        if ($email === '') {
            $erreurs['email'] = "L'adresse email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs['email'] = 'Veuillez saisir une adresse email valide.';
        }

        // Validation du champ mot de passe
        if ($mdp === '') {
            $erreurs['mdp'] = 'Le mot de passe est obligatoire.';
        }

        // S'il y a déjà des erreurs de formulaire
        if (!empty($erreurs)) {
            http_response_code(422);

            $this->view('auth/login', [
                'erreurs' => $erreurs,
                'email' => $email,
            ]);

            return;
        }

        try {
            $user = $this->authService->login($email, $mdp);

            // Si le visiteur venait du panier, on le renvoie vers le panier
            if (!empty($_SESSION['redirect_after_login'])) {
                $destination = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);

                $this->redirect($destination);
                return;
            }

            // Sinon, redirection normale selon le rôle
            $this->redirectAfterLogin($user['role']);
        } catch (AuthException $e) {

            http_response_code(401);

            $this->view('auth/login', [
                'erreurs' => [
                    'mdp' => $e->getMessage(),
                ],
                'email' => $email,
            ]);
        }
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function logout(): void
    {
        $this->authService->logout();

        $this->redirect('/login');
    }

    /**
     * Redirection selon le rôle.
     */
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
