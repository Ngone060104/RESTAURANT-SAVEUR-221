<?php

namespace App\Repositories;

use App\Models\LigneCommande;
use PDO;

/**
 * Pas de RepositoryInterface ici : une ligne de commande n'existe
 * jamais seule. Elle est créée dans le contexte d'une commande.
 */
class LigneCommandeRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByCommande(int $commandeId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT
                lc.*,
                p.nom AS produit_nom,
                p.image AS produit_image
            FROM lignes_commande lc
            JOIN produits p ON p.id = lc.produit_id
            WHERE lc.commande_id = :commande_id
            ORDER BY lc.id
        ');

        $stmt->execute([
            'commande_id' => $commandeId,
        ]);

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    public function create(
        int $commandeId,
        int $produitId,
        int $quantite,
        float $prixUnitaire
    ): int {
        $stmt = $this->pdo->prepare('
            INSERT INTO lignes_commande (
                quantite,
                prix_unitaire,
                montant_ligne,
                commande_id,
                produit_id
            )
            VALUES (
                :quantite,
                :prix_unitaire,
                :montant_ligne,
                :commande_id,
                :produit_id
            )
            RETURNING id
        ');

        $stmt->execute([
            'quantite' => $quantite,
            'prix_unitaire' => $prixUnitaire,
            'montant_ligne' => $prixUnitaire * $quantite,
            'commande_id' => $commandeId,
            'produit_id' => $produitId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    private function hydrate(object $row): LigneCommande
    {
        return new LigneCommande(
            (int) $row->id,
            (int) $row->quantite,
            (float) $row->prix_unitaire,
            (float) $row->montant_ligne,
            (int) $row->commande_id,
            (int) $row->produit_id,
            $row->produit_nom ?? null,
            $row->produit_image ?? null,
        );
    }
}