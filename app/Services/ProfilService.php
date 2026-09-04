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
    ) {}

    public function modifierInfos(int $clientId, array $data): bool
    {
        $required = ['nom', 'prenom', 'email', 'telephone', 'adresse'];

        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                throw new ValidationException("Le champ {$field} est obligatoire.");
            }
        }

        // Vérification email
        $email = trim($data['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Email invalide.');
        }

        // Récupération de l'utilisateur actuel
        $actuel = $this->utilisateurRepository->findById($clientId);

        if ($actuel === null) {
            throw new ValidationException('Utilisateur introuvable.');
        }

        // Vérification que l'email n'est pas déjà utilisé par un autre utilisateur
        if (
            $this->utilisateurRepository->emailExists($email, $clientId)
        ) {
            throw new ValidationException('Cet email est déjà utilisé.');
        }

        // Nettoyage du numéro de téléphone
        $telephone = preg_replace('/[\s.-]/', '', trim($data['telephone']));

        // Suppression du préfixe +221
        if (str_starts_with($telephone, '+221')) {
            $telephone = substr($telephone, 4);
        }

        // Vérification du format sénégalais
        if (!preg_match('/^[0-9]{9}$/', $telephone)) {
            throw new ValidationException('Numéro de téléphone invalide.');
        }

        // Vérification que le téléphone n'est pas déjà utilisé
        // par un AUTRE client
        if ($this->clientRepository->telephoneExists($telephone, $clientId)) {
            throw new ValidationException('Ce numéro de téléphone est déjà utilisé.');
        }

        return $this->clientRepository->update($clientId, [
            'nom' => trim($data['nom']),
            'prenom' => trim($data['prenom']),
            'email' => $email,
            'telephone' => $telephone,
            'adresse' => trim($data['adresse']),
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
