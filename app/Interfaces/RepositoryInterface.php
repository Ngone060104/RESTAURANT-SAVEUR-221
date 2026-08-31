<?php

namespace App\Interfaces;

/**
 * Contrat CRUD commun à tous les repositories (produits, catégories,
 * commandes, utilisateurs...). Chaque repository concret l'implémente
 * avec PDO (atelier 13 - repository/PDO).
 */
interface RepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?object;

    public function create(array $data): int;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
