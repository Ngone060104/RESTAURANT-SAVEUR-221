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
    ) {}

    /**
     * Inscription d'un client (visiteur -> client).
     */
    public function register(array $data): int
    {
        $this->validateRegistration($data);
        if ($this->utilisateurRepository->emailExists($data['email'])) {
            throw new ValidationException(
                'Cet email est déjà utilisé.',
                [
                    'email' => 'Cet email est déjà utilisé.'
                ]
            );
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
        $errors = [];

        // Prénom
        if (empty(trim($data['prenom'] ?? ''))) {
            $errors['prenom'] = 'Le prénom est obligatoire.';
        }

        // Nom
        if (empty(trim($data['nom'] ?? ''))) {
            $errors['nom'] = 'Le nom est obligatoire.';
        }

        // Email
        $email = trim($data['email'] ?? '');

        if ($email === '') {
            $errors['email'] = 'L’adresse email est obligatoire.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Veuillez saisir une adresse email valide.';
        }

        // Téléphone
        $telephone = trim($data['telephone'] ?? '');

        if ($telephone === '') {
            $errors['telephone'] = 'Le numéro de téléphone est obligatoire.';
        } elseif (!preg_match('/^(?:\+221)?[0-9]{9}$/', preg_replace('/[\s.-]/', '', $telephone))) {
            $errors['telephone'] = 'Veuillez saisir un numéro sénégalais valide.';
        }

        // Adresse
        if (empty(trim($data['adresse'] ?? ''))) {
            $errors['adresse'] = 'L’adresse de livraison est obligatoire.';
        }

        // Mot de passe
        $mdp = $data['mdp'] ?? '';

        if ($mdp === '') {
            $errors['mdp'] = 'Le mot de passe est obligatoire.';
        } elseif (strlen($mdp) < 6) {
            $errors['mdp'] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        // Confirmation du mot de passe
        $confirmation = $data['confirmation_mdp'] ?? '';

        if ($confirmation === '') {
            $errors['confirmation_mdp'] = 'Veuillez confirmer votre mot de passe.';
        } elseif ($mdp !== $confirmation) {
            $errors['confirmation_mdp'] = 'Les mots de passe ne correspondent pas.';
        }

        // S'il y a des erreurs, on les retourne toutes
        if (!empty($errors)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs du formulaire.',
                $errors
            );
        }
    }
}
