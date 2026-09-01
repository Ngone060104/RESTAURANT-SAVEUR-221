<?php

namespace App\Models;

class Paiement
{
    public function __construct(
        private int $id,
        private float $montant,
        private string $datePaiement,
        private int $commandeId,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function getDatePaiement(): string
    {
        return $this->datePaiement;
    }

    public function getCommandeId(): int
    {
        return $this->commandeId;
    }
}
