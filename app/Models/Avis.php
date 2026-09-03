<?php

namespace App\Models;

class Avis
{
    public function __construct(
        private int $id,
        private int $note,
        private ?string $commentaire,
        private string $dateAvis,
        private int $clientId,
        private int $commandeId,
        private ?string $clientNom = null,
        private ?string $clientPrenom = null,
        private ?string $produitNom = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function getDateAvis(): string
    {
        return $this->dateAvis;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getCommandeId(): int
    {
        return $this->commandeId;
    }

     public function getClientNomComplet(): ?string
    {
        if ($this->clientPrenom === null || $this->clientNom === null) {
            return null;
        }

        return "{$this->clientPrenom} {$this->clientNom}";
    }

    public function getProduitNom(): ?string
    {
        return $this->produitNom;
    }
}
