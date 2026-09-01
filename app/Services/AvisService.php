<?php

namespace App\Services;

use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Repositories\AvisRepository;
use App\Repositories\CommandeRepository;
use PDOException;

/**
 * Règles métier n°10 et n°11 : un seul avis par commande, uniquement
 * après RETIREE. Déjà garanties en base (contrainte UNIQUE + trigger),
 * ce service se contente de vérifier la propriété de la commande et de
 * traduire les deux erreurs SQL possibles en messages distincts.
 */
class AvisService
{
    private const CODE_VIOLATION_UNIQUE = '23505';

    public function __construct(
        private AvisRepository $avisRepository,
        private CommandeRepository $commandeRepository,
    ) {
    }

    public function laisserAvis(int $clientId, int $commandeId, int $note, ?string $commentaire): int
    {
        if ($note < 1 || $note > 5) {
            throw new ValidationException('La note doit être comprise entre 1 et 5.');
        }

        $commande = $this->commandeRepository->findCommandeById($commandeId);

        if ($commande === null) {
            throw new ValidationException('Commande introuvable.');
        }

        if (!$commande->appartientA($clientId)) {
            throw new AuthException('Cette commande ne vous appartient pas.');
        }

        try {
            return $this->avisRepository->create($clientId, $commandeId, $note, $commentaire);
        } catch (PDOException $e) {
            if ($e->getCode() === self::CODE_VIOLATION_UNIQUE) {
                throw new ValidationException('Vous avez déjà laissé un avis pour cette commande.');
            }

            throw new ValidationException(
                "Un avis n'est possible qu'une fois la commande retirée."
            );
        }
    }

    public function supprimer(int $id): bool
    {
        return $this->avisRepository->delete($id);
    }
}
