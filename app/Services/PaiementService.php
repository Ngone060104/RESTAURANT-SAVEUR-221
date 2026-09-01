<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\CommandeRepository;
use App\Repositories\PaiementRepository;
use PDOException;

/**
 * Règle métier n°12 : "un paiement ne peut pas dépasser le montant
 * restant." Elle est déjà garantie par le trigger PostgreSQL
 * fn_paiement_before_insert - ce service se contente de traduire
 * l'erreur SQL brute en message métier compréhensible.
 */
class PaiementService
{
    public function __construct(
        private PaiementRepository $paiementRepository,
        private CommandeRepository $commandeRepository,
    ) {
    }

    public function enregistrer(int $commandeId, float $montant): int
    {
        if ($montant <= 0) {
            throw new ValidationException('Le montant du paiement doit être positif.');
        }

        if ($this->commandeRepository->findCommandeById($commandeId) === null) {
            throw new ValidationException('Commande introuvable.');
        }

        try {
            return $this->paiementRepository->create($commandeId, $montant);
        } catch (PDOException $e) {
            throw new ValidationException(
                'Ce paiement dépasse le montant restant de la commande.'
            );
        }
    }

    public function getStatut(int $commandeId): ?object
    {
        return $this->paiementRepository->getStatutCommande($commandeId);
    }
}
