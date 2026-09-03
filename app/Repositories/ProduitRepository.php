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
    

    public function findAll(): array
    {
        $stmt = $this->pdo->query($this->baseQuery() . ' ORDER BY p.nom');

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare($this->baseQuery() . ' WHERE p.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findProduitById(int $id): ?Produit
    {
        $row = $this->findById($id);

        return $row ? $this->hydrate($row) : null;
    }

    public function findDisponibles(): array
    {
        $stmt = $this->pdo->query(
            $this->baseQuery() . " WHERE p.statut = 'disponible' ORDER BY p.nom"
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

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
        JOIN categories c ON c.id = p.categorie_id
        WHERE 1 = 1
    ';

    $params = [];

    // Filtre par catégorie
    if ($categorieId !== null) {
        $sql .= ' AND p.categorie_id = :categorie_id';
        $params['categorie_id'] = $categorieId;
    }

    // Filtre par disponibilité
    if ($statut !== null) {
        $sql .= ' AND p.statut = :statut';
        $params['statut'] = $statut;
    }

    // Recherche
    if ($terme !== null && $terme !== '') {
        $sql .= ' AND (
            p.nom ILIKE :terme
            OR p.description ILIKE :terme
        )';

        $params['terme'] = '%' . $terme . '%';
    }

    // Tri alphabétique
    $sql .= ' ORDER BY p.nom ASC';

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return array_map([$this, 'hydrate'], $stmt->fetchAll());
}

    public function findByCategorie(int $categorieId): array
    {
        $stmt = $this->pdo->prepare(
            $this->baseQuery() . ' WHERE p.categorie_id = :categorie_id ORDER BY p.nom'
        );
        $stmt->execute(['categorie_id' => $categorieId]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function search(string $terme): array
    {
        $stmt = $this->pdo->prepare(
            $this->baseQuery() . ' WHERE p.nom ILIKE :terme ORDER BY p.nom'
        );
        $stmt->execute(['terme' => "%{$terme}%"]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    /**
     * Filtre combiné (catégorie et/ou disponibilité), les deux étant
     * optionnels. Utilisé par le tableau de bord gérant (VI - Gestion
     * des produits : "filtrer par catégorie / disponibilité").
     */
    public function filter(?int $categorieId = null, ?string $statut = null): array
    {
        $sql = $this->baseQuery() . ' WHERE 1 = 1';
        $params = [];

        if ($categorieId !== null) {
            $sql .= ' AND p.categorie_id = :categorie_id';
            $params['categorie_id'] = $categorieId;
        }

        if ($statut !== null) {
            $sql .= ' AND p.statut = :statut';
            $params['statut'] = $statut;
        }

        $sql .= ' ORDER BY p.nom';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    /**
     * Produits dont le stock est sous le seuil donné, mais pas encore
     * en rupture (stock > 0). Sert au tableau de bord gérant.
     */
    public function findStockFaible(int $seuil): array
    {
        $stmt = $this->pdo->prepare(
            $this->baseQuery() . ' WHERE p.stock > 0 AND p.stock <= :seuil ORDER BY p.stock'
        );
        $stmt->execute(['seuil' => $seuil]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findEnRupture(): array
    {
        $stmt = $this->pdo->query(
            $this->baseQuery() . " WHERE p.statut = 'en_rupture' ORDER BY p.nom"
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

     /**
     * Produits mis en avant sur la page d'accueil ("Plats les plus
     * plébiscités"). Pas de colonne "vedette" dans le schéma : on
     * prend simplement les premiers produits disponibles.
     */
    public function findVedettes(int $limite = 3): array
    {
        $stmt = $this->pdo->query(
            $this->baseQuery() . " WHERE p.statut = 'disponible' ORDER BY p.id LIMIT " . (int) $limite
        );

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO produits (nom, description, prix, stock, image, statut, categorie_id)
            VALUES (:nom, :description, :prix, :stock, :image, :statut, :categorie_id)
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

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE produits
            SET nom = :nom, description = :description, prix = :prix, stock = :stock,
                image = :image, statut = :statut, categorie_id = :categorie_id
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

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM produits WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function baseQuery(): string
    {
        return '
            SELECT p.*, c.libelle AS categorie_libelle
            FROM produits p
            JOIN categories c ON c.id = p.categorie_id
        ';
    }

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
            $row->categorie_libelle,
        );
    }
}
