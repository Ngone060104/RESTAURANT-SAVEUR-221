<?php

namespace App\Repositories;

use App\Exceptions\ValidationException;
use App\Interfaces\RepositoryInterface;
use App\Models\Categorie;
use PDO;
use PDOException;

class CategorieRepository implements RepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM categories ORDER BY libelle');

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findByLibelle(string $libelle): ?object
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE libelle = :libelle');
        $stmt->execute(['libelle' => $libelle]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function search(string $terme): array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM categories WHERE libelle ILIKE :terme ORDER BY libelle
        ');
        $stmt->execute(['terme' => "%{$terme}%"]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO categories (libelle, description)
            VALUES (:libelle, :description)
            RETURNING id
        ');
        $stmt->execute([
            'libelle' => $data['libelle'],
            'description' => $data['description'] ?? null,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE categories SET libelle = :libelle, description = :description WHERE id = :id
        ');

        return $stmt->execute([
            'id' => $id,
            'libelle' => $data['libelle'],
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Règle métier n°9 : une catégorie contenant des produits ne peut
     * pas être supprimée. La FK produits.categorie_id est ON DELETE
     * RESTRICT, donc PostgreSQL refuserait de toute façon le DELETE ;
     * on transforme cette erreur SQL brute en message métier clair.
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = :id');

            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new ValidationException(
                'Impossible de supprimer une catégorie qui contient encore des produits.'
            );
        }
    }

    private function hydrate(object $row): Categorie
    {
        return new Categorie((int) $row->id, $row->libelle, $row->description);
    }
}
