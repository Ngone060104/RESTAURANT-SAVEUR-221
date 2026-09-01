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
}
