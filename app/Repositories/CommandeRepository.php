<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Commande;
use PDO;

class CommandeRepository implements RepositoryInterface
{
    public function __construct(private PDO $pdo) {}


    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM commandes ORDER BY date_commande DESC');

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare('SELECT * FROM commandes WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findCommandeById(int $id): ?Commande
    {
        $row = $this->findById($id);

        return $row ? $this->hydrate($row) : null;
    }

    public function findByClient(int $clientId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM commandes WHERE client_id = :client_id ORDER BY date_commande DESC'
        );
        $stmt->execute(['client_id' => $clientId]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findByStatut(string $statut): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM commandes WHERE statut = :statut ORDER BY date_commande DESC'
        );
        $stmt->execute(['statut' => $statut]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO commandes (montant_total, statut, client_id)
            VALUES (:montant_total, :statut, :client_id)
            RETURNING id
        ');
        $stmt->execute([
            'montant_total' => $data['montant_total'],
            'statut' => $data['statut'] ?? 'EN_ATTENTE',
            'client_id' => $data['client_id'],
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function getCaJour(): float
    {
        $stmt = $this->pdo->query('
        SELECT COALESCE(SUM(p.montant), 0)
        FROM paiements p
        WHERE p.date_paiement::date = CURRENT_DATE
    ');

        return (float) $stmt->fetchColumn();
    }

    public function getCaSemaine(): float
    {
        $stmt = $this->pdo->query('
        SELECT COALESCE(SUM(p.montant), 0)
        FROM paiements p
        WHERE p.date_paiement >= date_trunc(\'week\', CURRENT_DATE)
          AND p.date_paiement < date_trunc(\'week\', CURRENT_DATE)
              + INTERVAL \'1 week\'
    ');

        return (float) $stmt->fetchColumn();
    }

    public function getCaMois(): float
    {
        $stmt = $this->pdo->query('
        SELECT COALESCE(SUM(p.montant), 0)
        FROM paiements p
        WHERE p.date_paiement >= date_trunc(\'month\', CURRENT_DATE)
          AND p.date_paiement < date_trunc(\'month\', CURRENT_DATE)
              + INTERVAL \'1 month\'
    ');

        return (float) $stmt->fetchColumn();
    }

    public function countCommandes(): int
    {
        $stmt = $this->pdo->query('
        SELECT COUNT(*)
        FROM commandes
    ');

        return (int) $stmt->fetchColumn();
    }

    public function countCommandesEnCours(): int
    {
        $stmt = $this->pdo->query('
        SELECT COUNT(*)
        FROM commandes
        WHERE statut IN (
            \'EN_ATTENTE\',
            \'EN_PREPARATION\',
            \'PRETE\'
        )
    ');

        return (int) $stmt->fetchColumn();
    }

    public function countCommandesParStatut(): array
    {
        $stmt = $this->pdo->query('
        SELECT
            statut,
            COUNT(*) AS nombre
        FROM commandes
        GROUP BY statut
        ORDER BY statut
    ');

        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE commandes SET statut = :statut WHERE id = :id');

        return $stmt->execute(['id' => $id, 'statut' => $data['statut']]);
    }

    /**
     * Changement de statut. Si le nouveau statut est ANNULEE, le trigger
     * PostgreSQL fn_commande_annulation restaure automatiquement le
     * stock de tous les produits de la commande (règle métier n°8) -
     * rien à faire côté PHP.
     */
    public function changerStatut(int $id, string $statut): bool
    {
        return $this->update($id, ['statut' => $statut]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM commandes WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(object $row): Commande
    {
        return new Commande(
            (int) $row->id,
            $row->date_commande,
            (float) $row->montant_total,
            $row->statut,
            (int) $row->client_id,
        );
    }
}
