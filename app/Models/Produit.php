<?php

namespace App\Models;

/**
 * Représente une ligne de la table `produits`, éventuellement enrichie
 * du libellé de sa catégorie (quand hydraté depuis une requête avec JOIN).
 */
class Produit
{
    public function __construct(
        private int $id,
        private string $nom,
        private ?string $description,
        private float $prix,
        private int $stock,
        private ?string $image,
        private string $statut, // 'disponible' ou 'en_rupture'
        private int $categorieId,
        private ?string $categorieLibelle = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function isDisponible(): bool
    {
        return $this->statut === 'disponible';
    }

    public function getCategorieId(): int
    {
        return $this->categorieId;
    }

    public function getCategorieLibelle(): ?string
    {
        return $this->categorieLibelle;
    }
}
