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
    public function __construct(private PDO $pdo) {}


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
        SELECT
            p.*,
            c.client_id,
            c.montant_total,
            CONCAT(u.prenom, \' \', u.nom) AS client_nom_complet,
            v.montant_paye,
            v.montant_restant,
            v.statut_paiement
        FROM paiements p
        INNER JOIN commandes c
            ON c.id = p.commande_id
        INNER JOIN clients cl
            ON cl.id = c.client_id
        INNER JOIN utilisateurs u
            ON u.id = cl.id
        LEFT JOIN vue_statut_paiement v
            ON v.commande_id = c.id
        ORDER BY p.date_paiement DESC
    ');

        return $stmt->fetchAll();
    }

    public function findByStatut(string $statut): array
    {
        $stmt = $this->pdo->prepare('
        SELECT
            p.*,
            c.client_id,
            c.montant_total,
            CONCAT(u.prenom, \' \', u.nom) AS client_nom_complet,
            v.montant_paye,
            v.montant_restant,
            v.statut_paiement
        FROM paiements p
        INNER JOIN commandes c
            ON c.id = p.commande_id
        INNER JOIN clients cl
            ON cl.id = c.client_id
        INNER JOIN utilisateurs u
            ON u.id = cl.id
        LEFT JOIN vue_statut_paiement v
            ON v.commande_id = c.id
        WHERE v.statut_paiement = :statut
        ORDER BY p.date_paiement DESC
    ');

        $stmt->execute([
            'statut' => $statut,
        ]);

        return $stmt->fetchAll();
    }

    public function search(string $terme): array
    {
        $stmt = $this->pdo->prepare('
        SELECT
            p.*,
            c.client_id,
            c.montant_total,
            CONCAT(u.prenom, \' \', u.nom) AS client_nom_complet,
            v.montant_paye,
            v.montant_restant,
            v.statut_paiement
        FROM paiements p
        INNER JOIN commandes c
            ON c.id = p.commande_id
        INNER JOIN clients cl
            ON cl.id = c.client_id
        INNER JOIN utilisateurs u
            ON u.id = cl.id
        LEFT JOIN vue_statut_paiement v
            ON v.commande_id = c.id
        WHERE
            CAST(p.commande_id AS TEXT) ILIKE :terme
            OR CONCAT(u.prenom, \' \', u.nom) ILIKE :terme
        ORDER BY p.date_paiement DESC
    ');

        $stmt->execute([
            'terme' => '%' . $terme . '%',
        ]);

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
        $stmt = $this->pdo->query('
        SELECT
            v.*,
            c.client_id,
            CONCAT(u.prenom, \' \', u.nom) AS client_nom_complet
        FROM vue_statut_paiement v
        INNER JOIN commandes c
            ON c.id = v.commande_id
        INNER JOIN clients cl
            ON cl.id = c.client_id
        INNER JOIN utilisateurs u
            ON u.id = cl.id
        WHERE v.statut_paiement = \'IMPAYEE\'
        ORDER BY v.commande_id DESC
    ');

        return $stmt->fetchAll();
    }

    public function findCommandesPartiellementPayees(): array
    {
        $stmt = $this->pdo->query('
        SELECT
            v.*,
            c.client_id,
            CONCAT(u.prenom, \' \', u.nom) AS client_nom_complet
        FROM vue_statut_paiement v
        INNER JOIN commandes c
            ON c.id = v.commande_id
        INNER JOIN clients cl
            ON cl.id = c.client_id
        INNER JOIN utilisateurs u
            ON u.id = cl.id
        WHERE v.statut_paiement = \'PARTIELLEMENT_PAYEE\'
        ORDER BY v.commande_id DESC
    ');

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
