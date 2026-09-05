<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Produit;
use PDO;

class ProduitRepository implements RepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Tous les produits.
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            $this->baseQuery() . ' ORDER BY p.nom ASC'
        );

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Recherche d'un produit par son ID.
     *
     * Retourne directement un Produit.
     */
    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare(
            $this->baseQuery() . ' WHERE p.id = :id'
        );

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch();

        return $row
            ? $this->hydrate($row)
            : null;
    }

    /**
     * Recherche d'un produit par son ID.
     */
    public function findProduitById(int $id): ?Produit
    {
        $produit = $this->findById($id);

        return $produit instanceof Produit
            ? $produit
            : null;
    }

    /**
     * Produits disponibles.
     */
    public function findDisponibles(): array
    {
        $stmt = $this->pdo->query(
            $this->baseQuery()
            . " WHERE p.statut = 'disponible'"
            . ' ORDER BY p.nom ASC'
        );

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Catalogue avec filtres combinés :
     * - catégorie
     * - disponibilité
     * - recherche par nom ou description
     */
    public function findCatalogue(
        ?int $categorieId = null,
        ?string $statut = null,
        ?string $terme = null
    ): array {

        $sql = '
            SELECT
                p.*,
                c.libelle AS categorie_libelle
            FROM produits p
            JOIN categories c
                ON c.id = p.categorie_id
            WHERE 1 = 1
        ';

        $params = [];

        /*
         * FILTRE CATÉGORIE
         */
        if ($categorieId !== null) {

            $sql .= '
                AND p.categorie_id = :categorie_id
            ';

            $params['categorie_id'] = $categorieId;
        }

        /*
         * FILTRE DISPONIBILITÉ
         */
        if ($statut !== null && $statut !== '') {

            $sql .= '
                AND p.statut = :statut
            ';

            $params['statut'] = $statut;
        }

        /*
         * RECHERCHE
         *
         * Recherche dans :
         * - nom du produit
         * - description / ingrédients
         */
        if ($terme !== null && $terme !== '') {

            $sql .= '
                AND (
                    p.nom ILIKE :terme
                    OR p.description ILIKE :terme
                )
            ';

            $params['terme'] = '%' . $terme . '%';
        }

        /*
         * TRI
         */
        $sql .= '
            ORDER BY p.nom ASC
        ';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Produits d'une catégorie.
     */
    public function findByCategorie(int $categorieId): array
    {
        $stmt = $this->pdo->prepare(
            $this->baseQuery()
            . ' WHERE p.categorie_id = :categorie_id'
            . ' ORDER BY p.nom ASC'
        );

        $stmt->execute([
            'categorie_id' => $categorieId
        ]);

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Recherche simple par nom.
     *
     * Conservée pour les autres parties de l'application.
     */
    public function search(string $terme): array
    {
        $stmt = $this->pdo->prepare(
            $this->baseQuery()
            . ' WHERE p.nom ILIKE :terme'
            . ' ORDER BY p.nom ASC'
        );

        $stmt->execute([
            'terme' => '%' . $terme . '%'
        ]);

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Filtre par catégorie et/ou disponibilité.
     *
     * Conservé pour le dashboard et les autres utilisations.
     */
    public function filter(
        ?int $categorieId = null,
        ?string $statut = null
    ): array {

        $sql = $this->baseQuery() . '
            WHERE 1 = 1
        ';

        $params = [];

        if ($categorieId !== null) {

            $sql .= '
                AND p.categorie_id = :categorie_id
            ';

            $params['categorie_id'] = $categorieId;
        }

        if ($statut !== null && $statut !== '') {

            $sql .= '
                AND p.statut = :statut
            ';

            $params['statut'] = $statut;
        }

        $sql .= '
            ORDER BY p.nom ASC
        ';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Produits ayant un stock faible.
     *
     * Le stock doit être :
     * - supérieur à 0
     * - inférieur ou égal au seuil.
     */
    public function findStockFaible(int $seuil): array
    {
        $stmt = $this->pdo->prepare(
            $this->baseQuery()
            . ' WHERE p.stock > 0'
            . ' AND p.stock <= :seuil'
            . ' ORDER BY p.stock ASC'
        );

        $stmt->execute([
            'seuil' => $seuil
        ]);

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Produits en rupture.
     */
    public function findEnRupture(): array
    {
        $stmt = $this->pdo->query(
            $this->baseQuery()
            . " WHERE p.statut = 'en_rupture'"
            . ' ORDER BY p.nom ASC'
        );

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Produits mis en avant sur la page d'accueil.
     *
     * Il n'y a pas de colonne "vedette" dans la base.
     * On prend donc les premiers produits disponibles.
     */
    public function findVedettes(int $limite = 3): array
    {
        $limite = max(1, $limite);

        $stmt = $this->pdo->query(
            $this->baseQuery()
            . " WHERE p.statut = 'disponible'"
            . ' ORDER BY p.id ASC'
            . ' LIMIT ' . (int) $limite
        );

        return array_map(
            [$this, 'hydrate'],
            $stmt->fetchAll()
        );
    }

    /**
     * Création d'un produit.
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO produits (
                nom,
                description,
                prix,
                stock,
                image,
                statut,
                categorie_id
            )
            VALUES (
                :nom,
                :description,
                :prix,
                :stock,
                :image,
                :statut,
                :categorie_id
            )
            RETURNING id
        ');

        $stmt->execute([
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null,
            'prix' => $data['prix'],
            'stock' => $data['stock'],
            'image' => $data['image'] ?? null,
            'statut' => $data['statut'],
            'categorie_id' => $data['categorie_id'],
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Modification d'un produit.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE produits
            SET
                nom = :nom,
                description = :description,
                prix = :prix,
                stock = :stock,
                image = :image,
                statut = :statut,
                categorie_id = :categorie_id
            WHERE id = :id
        ');

        return $stmt->execute([
            'id' => $id,
            'nom' => $data['nom'],
            'description' => $data['description'] ?? null,
            'prix' => $data['prix'],
            'stock' => $data['stock'],
            'image' => $data['image'] ?? null,
            'statut' => $data['statut'],
            'categorie_id' => $data['categorie_id'],
        ]);
    }

    /**
     * Suppression d'un produit.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM produits WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }

    /**
     * Requête de base utilisée par les méthodes
     * qui ont besoin des informations de catégorie.
     */
    private function baseQuery(): string
    {
        return '
            SELECT
                p.*,
                c.libelle AS categorie_libelle
            FROM produits p
            JOIN categories c
                ON c.id = p.categorie_id
        ';
    }

    /**
     * Transformation d'une ligne SQL en objet Produit.
     */
    private function hydrate(object $row): Produit
    {
        return new Produit(
            (int) $row->id,
            $row->nom,
            $row->description,
            (float) $row->prix,
            (int) $row->stock,
            $row->image,
            $row->statut,
            (int) $row->categorie_id,
            $row->categorie_libelle ?? null,
        );
    }
}