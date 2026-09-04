<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\ClientRepository;
use App\Repositories\UtilisateurRepository;

class ProfilService
{
    public function __construct(
        private ClientRepository $clientRepository,
        private UtilisateurRepository $utilisateurRepository,
        private PasswordHasher $hasher,
    ) {
    }

    /**
     * Modifier les informations personnelles du client.
     */
    public function modifierInfos(int $clientId, array $data): bool
    {
        $erreurs = [];

        /*
        |--------------------------------------------------------------------------
        | Récupération de l'utilisateur
        |--------------------------------------------------------------------------
        */
        $utilisateur = $this->utilisateurRepository->findById($clientId);

        if ($utilisateur === null) {
            throw new ValidationException(
                'Utilisateur introuvable.',
                [
                    'general' => 'Utilisateur introuvable.',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Récupération des champs
        |--------------------------------------------------------------------------
        */
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $telephone = trim((string) ($data['telephone'] ?? ''));
        $adresse = trim((string) ($data['adresse'] ?? ''));

        /*
        |--------------------------------------------------------------------------
        | Validation NOM
        |--------------------------------------------------------------------------
        */
        if ($nom === '') {
            $erreurs['nom'] = 'Le nom est obligatoire.';
        }

        /*
        |--------------------------------------------------------------------------
        | Validation PRÉNOM
        |--------------------------------------------------------------------------
        */
        if ($prenom === '') {
            $erreurs['prenom'] = 'Le prénom est obligatoire.';
        }

        /*
        |--------------------------------------------------------------------------
        | Validation EMAIL
        |--------------------------------------------------------------------------
        */
        if ($email === '') {
            $erreurs['email'] = "L'adresse email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs['email'] = 'Veuillez saisir une adresse email valide.';
        }

        /*
        |--------------------------------------------------------------------------
        | Validation TÉLÉPHONE
        |--------------------------------------------------------------------------
        */
        if ($telephone === '') {
            $erreurs['telephone'] = 'Le numéro de téléphone est obligatoire.';
        } else {
            /*
            | On accepte :
            | 771234567
            | +221771234567
            | +221 77 123 45 67
            | 77 123 45 67
            */
            $telephoneNormalise = preg_replace('/[\s.\-()]/', '', $telephone);

            if (str_starts_with($telephoneNormalise, '+221')) {
                $telephoneNormalise = substr($telephoneNormalise, 4);
            }

            if (!preg_match('/^[0-9]{9}$/', $telephoneNormalise)) {
                $erreurs['telephone'] =
                    'Le numéro doit contenir 9 chiffres.';
            } else {
                $telephone = $telephoneNormalise;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validation ADRESSE
        |--------------------------------------------------------------------------
        */
        if ($adresse === '') {
            $erreurs['adresse'] = "L'adresse de livraison est obligatoire.";
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification email déjà utilisé
        |--------------------------------------------------------------------------
        */
        if (!isset($erreurs['email'])) {
            if (
                $this->utilisateurRepository->emailExists(
                    $email,
                    $clientId
                )
            ) {
                $erreurs['email'] =
                    'Cette adresse email est déjà utilisée.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification téléphone déjà utilisé
        |--------------------------------------------------------------------------
        */
        if (!isset($erreurs['telephone'])) {
            if (
                $this->clientRepository->telephoneExists(
                    $telephone,
                    $clientId
                )
            ) {
                $erreurs['telephone'] =
                    'Ce numéro de téléphone est déjà utilisé.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | S'il y a des erreurs
        |--------------------------------------------------------------------------
        */
        if (!empty($erreurs)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs.',
                $erreurs
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */
        return $this->clientRepository->update(
            $clientId,
            [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'telephone' => $telephone,
                'adresse' => $adresse,
                'actif' => $utilisateur->actif,
            ]
        );
    }

    /**
     * Modifier le mot de passe.
     */
    public function changerMotDePasse(
        int $clientId,
        string $ancienMdp,
        string $nouveauMdp,
        string $confirmationMdp
    ): bool {
        $erreurs = [];

        /*
        |--------------------------------------------------------------------------
        | Récupération utilisateur
        |--------------------------------------------------------------------------
        */
        $utilisateur = $this->utilisateurRepository->findById($clientId);

        if ($utilisateur === null) {
            throw new ValidationException(
                'Utilisateur introuvable.',
                [
                    'general' => 'Utilisateur introuvable.',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ancien mot de passe
        |--------------------------------------------------------------------------
        */
        if (trim($ancienMdp) === '') {
            $erreurs['ancien_mdp'] =
                "L'ancien mot de passe est obligatoire.";
        } elseif (
            !$this->hasher->verify(
                $ancienMdp,
                $utilisateur->mdp
            )
        ) {
            $erreurs['ancien_mdp'] =
                "L'ancien mot de passe est incorrect.";
        }

        /*
        |--------------------------------------------------------------------------
        | Nouveau mot de passe
        |--------------------------------------------------------------------------
        */
        if (trim($nouveauMdp) === '') {
            $erreurs['nouveau_mdp'] =
                'Le nouveau mot de passe est obligatoire.';
        } elseif (strlen($nouveauMdp) < 6) {
            $erreurs['nouveau_mdp'] =
                'Le nouveau mot de passe doit contenir au moins 6 caractères.';
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmation
        |--------------------------------------------------------------------------
        */
        if (trim($confirmationMdp) === '') {
            $erreurs['confirmation_mdp'] =
                'Veuillez confirmer le nouveau mot de passe.';
        } elseif ($nouveauMdp !== $confirmationMdp) {
            $erreurs['confirmation_mdp'] =
                'Les deux mots de passe ne correspondent pas.';
        }

        /*
        |--------------------------------------------------------------------------
        | Nouveau mot de passe différent de l'ancien
        |--------------------------------------------------------------------------
        */
        if (
            !isset($erreurs['ancien_mdp']) &&
            !isset($erreurs['nouveau_mdp']) &&
            $this->hasher->verify(
                $nouveauMdp,
                $utilisateur->mdp
            )
        ) {
            $erreurs['nouveau_mdp'] =
                "Le nouveau mot de passe doit être différent de l'ancien.";
        }

        /*
        |--------------------------------------------------------------------------
        | Retourner toutes les erreurs
        |--------------------------------------------------------------------------
        */
        if (!empty($erreurs)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs.',
                $erreurs
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Modification du mot de passe
        |--------------------------------------------------------------------------
        */
        return $this->utilisateurRepository->updateMotDePasse(
            $clientId,
            $this->hasher->hash($nouveauMdp)
        );
    }
}