<?php

namespace App\Repositories;

use App\Models\Paiement;
use PDO;

/**
 * Pas de RepositoryInterface ici : un paiement est un enregistrement
 * financier, on ne le modifie ni ne le supprime jamais après coup
 * (pas de update()/delete() qui aurait un sens métier).
 */
class PaiementRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    
    public function findByCommande(int $commandeId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM paiements WHERE commande_id = :commande_id ORDER BY date_paiement'
        );
        $stmt->execute(['commande_id' => $commandeId]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT p.*, c.client_id, c.montant_total
            FROM paiements p
            JOIN commandes c ON c.id = p.commande_id
            ORDER BY p.date_paiement DESC
        ');

        return $stmt->fetchAll();
    }

    /**
     * Insère un paiement. Le trigger PostgreSQL
     * fn_paiement_before_insert vérifie que le montant ne dépasse pas
     * le montant restant (règle métier n°12) - aucune vérification
     * de ce type côté PHP, pour ne pas dupliquer la règle.
     */
    public function create(int $commandeId, float $montant): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO paiements (montant, commande_id)
            VALUES (:montant, :commande_id)
            RETURNING id
        ');
        $stmt->execute(['montant' => $montant, 'commande_id' => $commandeId]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Utilise la vue vue_statut_paiement du script SQL, qui calcule
     * déjà montant_paye / montant_restant / statut_paiement.
     */
    public function getStatutCommande(int $commandeId): ?object
    {
        $stmt = $this->pdo->prepare('SELECT * FROM vue_statut_paiement WHERE commande_id = :id');
        $stmt->execute(['id' => $commandeId]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findCommandesImpayees(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM vue_statut_paiement WHERE statut_paiement = 'IMPAYEE'"
        );

        return $stmt->fetchAll();
    }

    public function findCommandesPartiellementPayees(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM vue_statut_paiement WHERE statut_paiement = 'PARTIELLEMENT_PAYEE'"
        );

        return $stmt->fetchAll();
    }

    private function hydrate(object $row): Paiement
    {
        return new Paiement(
            (int) $row->id,
            (float) $row->montant,
            $row->date_paiement,
            (int) $row->commande_id,
        );
    }
}
