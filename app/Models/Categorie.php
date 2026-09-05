<?php

namespace App\Models;

/**
 * Représente une ligne de la table `categories`.
 */
class Categorie
{
    public function __construct(
        private int $id,
        private string $libelle,
        private ?string $description,
         private int $nombreProduits = 0,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getNombreProduits(): int
    {
        return $this->nombreProduits;
    }
}