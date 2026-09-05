<?php

namespace App\Repositories;

use App\Exceptions\ValidationException;
use App\Interfaces\RepositoryInterface;
use App\Models\Categorie;
use PDO;
use PDOException;

class CategorieRepository implements RepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    /**
     * Récupérer toutes les catégories avec
     * le nombre de produits rattachés.
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT
                c.id,
                c.libelle,
                c.description,
                COUNT(p.id) AS nombre_produits
             FROM categories c
             LEFT JOIN produits p ON p.categorie_id = c.id
             GROUP BY c.id, c.libelle, c.description
             ORDER BY c.libelle'
        );

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Récupérer une catégorie par son ID.
     */
    public function findById(int $id): ?Categorie
    {
        $stmt = $this->pdo->prepare(
            'SELECT
            c.id,
            c.libelle,
            c.description,
            0 AS nombre_produits
         FROM categories c
         WHERE c.id = :id'
        );

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Récupérer une catégorie par son libellé.
     */
    public function findByLibelle(string $libelle): ?Categorie
    {
        $stmt = $this->pdo->prepare(
            'SELECT
            c.id,
            c.libelle,
            c.description,
            0 AS nombre_produits
         FROM categories c
         WHERE c.libelle = :libelle'
        );

        $stmt->execute([
            'libelle' => $libelle
        ]);

        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Rechercher des catégories avec le nombre
     * de produits rattachés.
     */
    public function search(string $terme): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT
                c.id,
                c.libelle,
                c.description,
                COUNT(p.id) AS nombre_produits
             FROM categories c
             LEFT JOIN produits p ON p.categorie_id = c.id
             WHERE c.libelle ILIKE :terme
             GROUP BY c.id, c.libelle, c.description
             ORDER BY c.libelle'
        );

        $stmt->execute([
            'terme' => "%{$terme}%"
        ]);

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Créer une catégorie.
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO categories (libelle, description)
             VALUES (:libelle, :description)
             RETURNING id'
        );

        $stmt->execute([
            'libelle' => $data['libelle'],
            'description' => $data['description'] ?? null,
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Modifier une catégorie.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE categories
             SET libelle = :libelle,
                 description = :description
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'libelle' => $data['libelle'],
            'description' => $data['description'] ?? null,
        ]);
    }

    /**
     * Supprimer une catégorie.
     *
     * PostgreSQL empêche la suppression si des produits
     * utilisent encore cette catégorie.
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare(
                'DELETE FROM categories WHERE id = :id'
            );

            return $stmt->execute([
                'id' => $id
            ]);
        } catch (PDOException $e) {
            throw new ValidationException(
                'Impossible de supprimer une catégorie qui contient encore des produits.'
            );
        }
    }

    /**
     * Transformer une ligne SQL en objet Categorie.
     */
    private function hydrate(object $row): Categorie
    {
        return new Categorie(
            (int) $row->id,
            $row->libelle,
            $row->description,
            (int) ($row->nombre_produits ?? 0)
        );
    }
}
