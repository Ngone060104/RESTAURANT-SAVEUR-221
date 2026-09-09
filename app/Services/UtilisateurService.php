<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\UtilisateurRepository;
use PDOException;

class UtilisateurService
{
    private const ROLES_INTERNES = ['ADMIN', 'GERANT'];

    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private PasswordHasher $hasher,
    ) {}

    /**
     * Créer un utilisateur interne.
     */
    public function creer(array $data): int
    {
        $donnees = $this->validerDonnees(
            $data,
            true
        );

        /*
         * Vérification de l'unicité de l'email.
         */
        if (
            $this->utilisateurRepository->emailExists(
                $donnees['email']
            )
        ) {
            throw new ValidationException(
                'Veuillez corriger les erreurs.',
                [
                    'email' =>
                    'Cet email appartient déjà à un autre utilisateur.',
                ]
            );
        }

        /*
         * Récupération de l'identifiant du rôle.
         */
        $roleId =
            $this->utilisateurRepository
            ->findRoleIdByLibelle(
                $donnees['role']
            );

        if ($roleId === null) {
            throw new ValidationException(
                'Veuillez corriger les erreurs.',
                [
                    'role_id' =>
                    'Le rôle sélectionné est invalide.',
                ]
            );
        }

        try {
            return $this->utilisateurRepository->create([
                'nom' => $donnees['nom'],
                'prenom' => $donnees['prenom'],
                'email' => $donnees['email'],
                'mdp' => $this->hasher->hash(
                    $donnees['mdp']
                ),
                'actif' => true,
                'role_id' => $roleId,
            ]);
        } catch (PDOException $e) {
            /*
             * PostgreSQL : violation de contrainte UNIQUE.
             * SQLSTATE 23505 = duplicate key.
             */
            if ($e->getCode() === '23505') {
                throw new ValidationException(
                    'Veuillez corriger les erreurs.',
                    [
                        'email' =>
                        'Cet email appartient déjà à un autre utilisateur.',
                    ]
                );
            }

            throw $e;
        }
    }

    /**
     * Modifier un utilisateur interne.
     */
    public function modifier(
        int $id,
        array $data
    ): bool {
        $erreurs = [];

        $nom = trim(
            (string) ($data['nom'] ?? '')
        );

        $prenom = trim(
            (string) ($data['prenom'] ?? '')
        );

        $email = trim(
            (string) ($data['email'] ?? '')
        );

        $roleId = (int) (
            $data['role_id'] ?? 0
        );

        /*
         * NOM
         */
        if ($nom === '') {
            $erreurs['nom'] =
                'Le nom est obligatoire.';
        }

        /*
         * PRÉNOM
         */
        if ($prenom === '') {
            $erreurs['prenom'] =
                'Le prénom est obligatoire.';
        }

        /*
         * EMAIL
         */
        if ($email === '') {
            $erreurs['email'] =
                "L'adresse email est obligatoire.";
        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $erreurs['email'] =
                "L'adresse email est invalide.";
        } elseif (
            $this->utilisateurRepository->emailExists(
                $email,
                $id
            )
        ) {
            $erreurs['email'] =
                'Cet email appartient déjà à un autre utilisateur.';
        }

        /*
         * RÔLE
         *
         * On récupère les vrais IDs en base
         * au lieu de supposer obligatoirement
         * que 1 = ADMIN et 2 = GERANT.
         */
        $role = '';

        if ($roleId <= 0) {
            $erreurs['role_id'] =
                'Le rôle est obligatoire.';
        } else {
            $roleAdminId =
                $this->utilisateurRepository
                ->findRoleIdByLibelle(
                    'ADMIN'
                );

            $roleGerantId =
                $this->utilisateurRepository
                ->findRoleIdByLibelle(
                    'GERANT'
                );

            if ($roleAdminId === $roleId) {
                $role = 'ADMIN';
            } elseif (
                $roleGerantId === $roleId
            ) {
                $role = 'GERANT';
            } else {
                $erreurs['role_id'] =
                    'Le rôle sélectionné est invalide.';
            }
        }

        /*
         * S'il existe plusieurs erreurs,
         * on les renvoie toutes.
         */
        if (!empty($erreurs)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs.',
                $erreurs
            );
        }

        /*
         * Vérification que l'utilisateur existe.
         */
        $actuel =
            $this->utilisateurRepository
            ->findById($id);

        if ($actuel === null) {
            throw new ValidationException(
                'Utilisateur introuvable.',
                [
                    'general' =>
                    'Utilisateur introuvable.',
                ]
            );
        }

        /*
         * Mise à jour.
         *
         * On conserve le statut actuel
         * actif / inactif.
         */
        try {
            return $this->utilisateurRepository->update(
                $id,
                [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'actif' => $actuel->isActif(),
                    'role_id' => $roleId,
                ]
            );
        } catch (PDOException $e) {
            /*
             * PostgreSQL :
             * 23505 = violation de contrainte UNIQUE.
             *
             * Cela évite qu'une erreur d'unicité
             * provoque une page 500.
             */
            if ($e->getCode() === '23505') {
                throw new ValidationException(
                    'Veuillez corriger les erreurs.',
                    [
                        'email' =>
                        'Cet email appartient déjà à un autre utilisateur.',
                    ]
                );
            }

            throw $e;
        }
    }

    /**
     * Activer un utilisateur.
     */
    public function activer(int $id): bool
    {
        return $this->toggleActif(
            $id,
            true
        );
    }

    /**
     * Désactiver un utilisateur.
     */
    public function desactiver(int $id): bool
    {
        return $this->toggleActif(
            $id,
            false
        );
    }

    /**
     * Supprimer un utilisateur.
     */
    public function supprimer(int $id): bool
    {
        $utilisateur =
            $this->utilisateurRepository
            ->findById($id);

        if ($utilisateur === null) {
            throw new ValidationException(
                'Utilisateur introuvable.'
            );
        }

        return $this->utilisateurRepository
            ->delete($id);
    }

    /**
     * Modifier le statut actif/inactif.
     */
    private function toggleActif(
        int $id,
        bool $actif
    ): bool {
        $utilisateur =
            $this->utilisateurRepository
            ->findById($id);

        if ($utilisateur === null) {
            throw new ValidationException(
                'Utilisateur introuvable.'
            );
        }

        return $this->utilisateurRepository
            ->updateActif($id, $actif);
    }

    /**
     * Validation commune pour la création.
     *
     * Le formulaire envoie role_id,
     * mais cette méthode retourne le libellé du rôle.
     *
     * @return array{
     *     nom:string,
     *     prenom:string,
     *     email:string,
     *     role:string,
     *     mdp:string
     * }
     */
    private function validerDonnees(
        array $data,
        bool $mdpObligatoire
    ): array {
        $erreurs = [];

        $nom = trim(
            (string) ($data['nom'] ?? '')
        );

        $prenom = trim(
            (string) ($data['prenom'] ?? '')
        );

        $email = trim(
            (string) ($data['email'] ?? '')
        );

        $roleId = (int) (
            $data['role_id'] ?? 0
        );

        $mdp = (string) (
            $data['mdp'] ?? ''
        );

        /*
         * NOM
         */
        if ($nom === '') {
            $erreurs['nom'] =
                'Le nom est obligatoire.';
        }

        /*
         * PRÉNOM
         */
        if ($prenom === '') {
            $erreurs['prenom'] =
                'Le prénom est obligatoire.';
        }

        /*
         * EMAIL
         */
        if ($email === '') {
            $erreurs['email'] =
                "L'adresse email est obligatoire.";
        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            $erreurs['email'] =
                'Veuillez saisir une adresse email valide.';
        }

        /*
         * MOT DE PASSE
         *
         * Obligatoire uniquement à la création.
         */
        if (
            $mdpObligatoire
            && $mdp === ''
        ) {
            $erreurs['mdp'] =
                'Le mot de passe est obligatoire.';
        } elseif (
            $mdpObligatoire
            && strlen($mdp) < 6
        ) {
            $erreurs['mdp'] =
                'Le mot de passe doit contenir au moins 6 caractères.';
        }

        /*
         * RÔLE
         */
        $role = '';

        if ($roleId <= 0) {
            $erreurs['role_id'] =
                'Le rôle est obligatoire.';
        } else {
            $roleAdminId =
                $this->utilisateurRepository
                ->findRoleIdByLibelle(
                    'ADMIN'
                );

            $roleGerantId =
                $this->utilisateurRepository
                ->findRoleIdByLibelle(
                    'GERANT'
                );

            if ($roleAdminId === $roleId) {
                $role = 'ADMIN';
            } elseif (
                $roleGerantId === $roleId
            ) {
                $role = 'GERANT';
            } else {
                $erreurs['role_id'] =
                    'Le rôle sélectionné est invalide.';
            }
        }

        /*
         * Retour des erreurs.
         */
        if (!empty($erreurs)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs.',
                $erreurs
            );
        }

        return [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'role' => $role,
            'mdp' => $mdp,
        ];
    }
}
