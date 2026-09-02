<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\UtilisateurRepository;

/**
 * Section "ESPACE ADMINISTRATEUR -> CRUD utilisateurs" : ajouter,
 * modifier, supprimer, rechercher, activer/désactiver, changer le rôle
 * d'un compte interne (ADMIN ou GERANT - jamais CLIENT, qui a son
 * propre flux d'inscription publique).
 */
class UtilisateurService
{
    private const ROLES_INTERNES = ['ADMIN', 'GERANT'];

    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private PasswordHasher $hasher,
    ) {
    }

    public function creer(array $data): int
    {
        $this->validate($data, mdpObligatoire: true);

        if ($this->utilisateurRepository->emailExists($data['email'])) {
            throw new ValidationException('Cet email est déjà utilisé.');
        }

        $roleId = $this->utilisateurRepository->findRoleIdByLibelle($data['role']);

        return $this->utilisateurRepository->create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mdp' => $this->hasher->hash($data['mdp']),
            'actif' => true,
            'role_id' => $roleId,
        ]);
    }

    public function modifier(int $id, array $data): bool
    {
        $this->validate($data, mdpObligatoire: false);

        $actuel = $this->utilisateurRepository->findById($id);

        if ($actuel === null) {
            throw new ValidationException('Utilisateur introuvable.');
        }

        if ($actuel->email !== $data['email'] && $this->utilisateurRepository->emailExists($data['email'])) {
            throw new ValidationException('Cet email est déjà utilisé.');
        }

        $roleId = $this->utilisateurRepository->findRoleIdByLibelle($data['role']);

        return $this->utilisateurRepository->update($id, [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'actif' => $actuel->actif,
            'role_id' => $roleId,
        ]);
    }

    public function activer(int $id): bool
    {
        return $this->toggleActif($id, true);
    }

    public function desactiver(int $id): bool
    {
        return $this->toggleActif($id, false);
    }

    public function supprimer(int $id): bool
    {
        return $this->utilisateurRepository->delete($id);
    }

    private function toggleActif(int $id, bool $actif): bool
    {
        $utilisateur = $this->utilisateurRepository->findById($id);

        if ($utilisateur === null) {
            throw new ValidationException('Utilisateur introuvable.');
        }

        return $this->utilisateurRepository->update($id, [
            'nom' => $utilisateur->nom,
            'prenom' => $utilisateur->prenom,
            'email' => $utilisateur->email,
            'actif' => $actif,
            'role_id' => $utilisateur->role_id,
        ]);
    }

    private function validate(array $data, bool $mdpObligatoire): void
    {
        $required = ['nom', 'prenom', 'email', 'role'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("Le champ {$field} est obligatoire.");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Email invalide.');
        }

        if (!in_array($data['role'], self::ROLES_INTERNES, true)) {
            throw new ValidationException('Le rôle doit être ADMIN ou GERANT.');
        }

        if ($mdpObligatoire && strlen($data['mdp'] ?? '') < 6) {
            throw new ValidationException('Le mot de passe doit contenir au moins 6 caractères.');
        }
    }
}
