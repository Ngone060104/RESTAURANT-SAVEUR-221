<?php

namespace App\Models;

/**
 * Un Client EST-UN Utilisateur (héritage - atelier 04), avec en plus
 * les colonnes propres à la table `clients` : telephone, adresse.
 * Reflète directement l'héritage de table du script SQL.
 */
class Client extends Utilisateur
{
    public function __construct(
        int $id,
        string $nom,
        string $prenom,
        string $email,
        bool $actif,
        ?string $dateCreation,
        private string $telephone,
        private string $adresse,
    ) {
        parent::__construct($id, $nom, $prenom, $email, $actif, 'CLIENT', $dateCreation);
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }
}
