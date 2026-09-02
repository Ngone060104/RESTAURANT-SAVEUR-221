<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\ClientRepository;
use App\Repositories\UtilisateurRepository;

/**
 * Section "Client -> Profil" : modifier nom/prénom/téléphone/adresse/
 * email, changer son mot de passe.
 */
class ProfilService
{
    public function __construct(
        private ClientRepository $clientRepository,
        private UtilisateurRepository $utilisateurRepository,
        private PasswordHasher $hasher,
    ) {
    }

    public function modifierInfos(int $clientId, array $data): bool
    {
        $required = ['nom', 'prenom', 'email', 'telephone', 'adresse'];

        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("Le champ {$field} est obligatoire.");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Email invalide.');
        }

        $actuel = $this->utilisateurRepository->findById($clientId);

        if ($actuel === null) {
            throw new ValidationException('Utilisateur introuvable.');
        }

        // On ne vérifie l'unicité que si l'email a réellement changé.
        if ($actuel->email !== $data['email'] && $this->utilisateurRepository->emailExists($data['email'])) {
            throw new ValidationException('Cet email est déjà utilisé.');
        }

        return $this->clientRepository->update($clientId, [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'adresse' => $data['adresse'],
            'actif' => $actuel->actif,
        ]);
    }

    public function changerMotDePasse(int $clientId, string $ancienMdp, string $nouveauMdp): bool
    {
        $utilisateur = $this->utilisateurRepository->findById($clientId);

        if ($utilisateur === null) {
            throw new ValidationException('Utilisateur introuvable.');
        }

        if (!$this->hasher->verify($ancienMdp, $utilisateur->mdp)) {
            throw new ValidationException("L'ancien mot de passe est incorrect.");
        }

        if (strlen($nouveauMdp) < 6) {
            throw new ValidationException('Le nouveau mot de passe doit contenir au moins 6 caractères.');
        }

        return $this->utilisateurRepository->updateMotDePasse($clientId, $this->hasher->hash($nouveauMdp));
    }
}
