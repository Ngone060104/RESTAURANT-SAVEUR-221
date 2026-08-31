<?php

namespace App\Services;

use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Repositories\ClientRepository;
use App\Repositories\UtilisateurRepository;

/**
 * Centralise les règles métier d'authentification (règles 1, 2, 3 du
 * cahier des charges) : email unique, mot de passe >= 6 caractères,
 * compte désactivé refusé, session ouverte après connexion.
 */
class AuthService
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private ClientRepository $clientRepository,
        private PasswordHasher $hasher,
    ) {
    }

    /**
     * Inscription d'un client (visiteur -> client).
     */
    public function register(array $data): int
    {
        $this->validateRegistration($data);

        if ($this->utilisateurRepository->emailExists($data['email'])) {
            throw new ValidationException('Cet email est déjà utilisé.');
        }

        return $this->clientRepository->create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mdp' => $this->hasher->hash($data['mdp']),
            'telephone' => $data['telephone'],
            'adresse' => $data['adresse'],
        ]);
    }

    /**
     * Connexion : valide contre la table utilisateurs, tous rôles confondus
     * (client, gérant, admin), puis ouvre la session.
     *
     * @return array{id:int, nom:string, prenom:string, email:string, role:string}
     */
    public function login(string $email, string $password): array
    {
        $user = $this->utilisateurRepository->findByEmailWithPassword($email);

        if ($user === null) {
            throw new AuthException('Email ou mot de passe incorrect.');
        }

        if (!$this->hasher->verify($password, $user->mdp)) {
            throw new AuthException('Email ou mot de passe incorrect.');
        }

        if (!$user->actif) {
            throw new AuthException('Ce compte a été désactivé.');
        }

        $sessionUser = [
            'id' => (int) $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $_SESSION['user'] = $sessionUser;

        return $sessionUser;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }

    public static function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    private function validateRegistration(array $data): void
    {
        $required = ['nom', 'prenom', 'email', 'mdp', 'telephone', 'adresse'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("Le champ {$field} est obligatoire.");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Email invalide.');
        }

        if (strlen($data['mdp']) < 6) {
            throw new ValidationException('Le mot de passe doit contenir au moins 6 caractères.');
        }
    }
}
