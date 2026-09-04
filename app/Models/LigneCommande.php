<?php

namespace App\Models;

class LigneCommande
{
    public function __construct(
        private int $id,
        private int $quantite,
        private float $prixUnitaire,
        private float $montantLigne,
        private int $commandeId,
        private int $produitId,
        private ?string $produitLibelle = null,
        private ?string $produitImage = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function getMontantLigne(): float
    {
        return $this->montantLigne;
    }

    public function getCommandeId(): int
    {
        return $this->commandeId;
    }

    public function getProduitId(): int
    {
        return $this->produitId;
    }

    public function getProduitLibelle(): ?string
    {
        return $this->produitLibelle;
    }

    public function getProduitImage(): ?string
    {
        return $this->produitImage;
    }
}