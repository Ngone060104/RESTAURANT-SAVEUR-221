<?php

namespace App\Models;

class Commande
{
    public function __construct(
        private int $id,
        private string $dateCommande,
        private float $montantTotal,
        private string $statut, // EN_ATTENTE, EN_PREPARATION, PRETE, RETIREE, ANNULEE
        private int $clientId,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDateCommande(): string
    {
        return $this->dateCommande;
    }

    public function getMontantTotal(): float
    {
        return $this->montantTotal;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function appartientA(int $clientId): bool
    {
        return $this->clientId === $clientId;
    }

    public function estAnnulable(): bool
    {
        return in_array($this->statut, ['EN_ATTENTE', 'EN_PREPARATION'], true);
    }

    public function peutRecevoirUnAvis(): bool
    {
        return $this->statut === 'RETIREE';
    }
}
