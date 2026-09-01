<?php

namespace App\Repositories;

use App\Models\Avis;
use PDO;

class AvisRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('
            SELECT a.*, u.nom AS client_nom, u.prenom AS client_prenom
            FROM avis a
            JOIN clients c ON c.id = a.client_id
            JOIN utilisateurs u ON u.id = c.id
            ORDER BY a.date_avis DESC
        ');

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findByCommande(int $commandeId): ?Avis
    {
        $stmt = $this->pdo->prepare('SELECT * FROM avis WHERE commande_id = :commande_id');
        $stmt->execute(['commande_id' => $commandeId]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Insère un avis. Les triggers PostgreSQL garantissent déjà :
     * - la contrainte UNIQUE(commande_id) -> règle n°10 (un seul avis
     *   par commande) ;
     * - fn_avis_before_insert -> règle n°11 (uniquement après RETIREE).
     * Aucune de ces deux vérifications n'est dupliquée côté PHP.
     */
    public function create(int $clientId, int $commandeId, int $note, ?string $commentaire): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO avis (note, commentaire, client_id, commande_id)
            VALUES (:note, :commentaire, :client_id, :commande_id)
            RETURNING id
        ');
        $stmt->execute([
            'note' => $note,
            'commentaire' => $commentaire,
            'client_id' => $clientId,
            'commande_id' => $commandeId,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM avis WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(object $row): Avis
    {
        return new Avis(
            (int) $row->id,
            (int) $row->note,
            $row->commentaire,
            $row->date_avis,
            (int) $row->client_id,
            (int) $row->commande_id,
        );
    }
}
