<?php

namespace App\Services;

use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Repositories\CommandeRepository;
use App\Repositories\LigneCommandeRepository;
use App\Repositories\PaiementRepository;
use PDO;
use PDOException;
use Throwable;

/**
 * Orchestre le flux "Panier -> Validation -> Création commande ->
 * Création des lignes -> Calcul du total -> Diminution du stock"
 * (section VI du cahier des charges).
 *
 * Le calcul du total et la diminution du stock sont volontairement
 * SIMPLES côté PHP : les règles 6, 7 et 8 (stock suffisant, décrémenté,
 * restauré à l'annulation) sont déjà garanties par les triggers
 * PostgreSQL du script SQL. Dupliquer cette logique ici créerait deux
 * sources de vérité qui pourraient diverger.
 */
class CommandeService
{
    private const STATUTS_VALIDES = ['EN_ATTENTE', 'EN_PREPARATION', 'PRETE', 'RETIREE', 'ANNULEE'];

    public function __construct(
        private PDO $pdo,
        private CommandeRepository $commandeRepository,
        private LigneCommandeRepository $ligneCommandeRepository,
        private PanierService $panierService,
         private PaiementRepository $paiementRepository,
    ) {
    }

    /**
     * Règle métier n°5 : une commande doit contenir au moins un article.
     * Règle métier n°6 : vérifiée par le trigger PostgreSQL à l'insertion
     * de chaque ligne (lève une exception SQL si le stock est insuffisant).
     */
    public function validerPanier(int $clientId): int
    {
        $lignes = $this->panierService->getLignes();

        if (empty($lignes)) {
            throw new ValidationException('Le panier est vide, impossible de valider une commande.');
        }

        $this->pdo->beginTransaction();

        try {
            $commandeId = $this->commandeRepository->create([
                'montant_total' => $this->panierService->getTotal(),
                'statut' => 'EN_ATTENTE',
                'client_id' => $clientId,
            ]);

            foreach ($lignes as $ligne) {
                $this->ligneCommandeRepository->create(
                    $commandeId,
                    $ligne['produit']->getId(),
                    $ligne['quantite'],
                    $ligne['produit']->getPrix(),
                );
            }

            $this->pdo->commit();
            $this->panierService->vider();

            return $commandeId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            // Le trigger PostgreSQL a refusé une ligne (stock insuffisant
            // entre l'ajout au panier et la validation).
            throw new ValidationException(
                'Stock insuffisant pour au moins un produit du panier. Merci de vérifier votre panier.'
            );
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * @return array{commande: \App\Models\Commande, lignes: \App\Models\LigneCommande[]}
     */
   public function getDetail(int $commandeId, int $clientId): array
{
    $commande = $this->commandeRepository->findCommandeById($commandeId);

    if ($commande === null) {
        throw new ValidationException('Commande introuvable.');
    }

    if (!$commande->appartientA($clientId)) {
        throw new AuthException("Cette commande ne vous appartient pas.");
    }

    $paiements = $this->paiementRepository->findByCommande($commandeId);

    $statutPaiement = $this->paiementRepository->getStatutCommande($commandeId);

    return [
        'commande' => $commande,

        'lignes' => $this->ligneCommandeRepository->findByCommande(
            $commandeId
        ),

        'paiements' => $paiements,

        'statutPaiement' => $statutPaiement,
    ];
}

    public function getHistoriqueClient(int $clientId): array
    {
        return $this->commandeRepository->findByClient($clientId);
    }

    /**
     * Changement de statut côté gérant (section VI - "modifier son statut").
     */
    public function changerStatut(int $commandeId, string $statut): bool
    {
        if (!in_array($statut, self::STATUTS_VALIDES, true)) {
            throw new ValidationException("Statut invalide : {$statut}.");
        }

        return $this->commandeRepository->changerStatut($commandeId, $statut);
    }

    /**
     * Annulation côté gérant. Le trigger fn_commande_annulation restaure
     * automatiquement le stock (règle n°8) dès que le statut passe à
     * ANNULEE - on se contente ici de vérifier qu'on n'annule pas une
     * commande déjà retirée par le client.
     */
    public function annuler(int $commandeId): bool
    {
        $commande = $this->commandeRepository->findCommandeById($commandeId);

        if ($commande === null) {
            throw new ValidationException('Commande introuvable.');
        }

        if (!$commande->estAnnulable()) {
            throw new ValidationException(
                "Impossible d'annuler une commande déjà {$commande->getStatut()}."
            );
        }

        return $this->commandeRepository->changerStatut($commandeId, 'ANNULEE');
    }
}
