<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Client;
use PDO;
use Throwable;

/**
 * Un Client est stocké sur DEUX tables (utilisateurs + clients),
 * fidèle à l'héritage de table du script SQL. create() utilise donc
 * une transaction PDO pour garantir que les deux INSERT réussissent
 * ensemble ou pas du tout.
 */
class ClientRepository implements RepositoryInterface
{
    public function __construct(
        private PDO $pdo,
        private UtilisateurRepository $utilisateurRepository,
    ) {
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query($this->baseQuery());

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare($this->baseQuery() . ' AND u.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function findClientById(int $id): ?Client
    {
        $stmt = $this->pdo->prepare($this->baseQuery() . ' AND u.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    /**
     * Inscription d'un client : insère la partie commune (utilisateurs)
     * puis la partie spécifique (clients), dans une transaction.
     *
     * $data attend : nom, prenom, email, mdp (déjà haché), telephone, adresse
     */
    public function create(array $data): int
    {
        $this->pdo->beginTransaction();

        try {
            $roleId = $this->utilisateurRepository->findRoleIdByLibelle('CLIENT');

            $userId = $this->utilisateurRepository->create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'mdp' => $data['mdp'],
                'actif' => true,
                'role_id' => $roleId,
            ]);

            $stmt = $this->pdo->prepare('
                INSERT INTO clients (id, telephone, adresse)
                VALUES (:id, :telephone, :adresse)
            ');
            $stmt->execute([
                'id' => $userId,
                'telephone' => $data['telephone'],
                'adresse' => $data['adresse'],
            ]);

            $this->pdo->commit();

            return $userId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $this->pdo->beginTransaction();

        try {
            $this->utilisateurRepository->update($id, [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'actif' => $data['actif'] ?? true,
                'role_id' => $this->utilisateurRepository->findRoleIdByLibelle('CLIENT'),
            ]);

            $stmt = $this->pdo->prepare('
                UPDATE clients SET telephone = :telephone, adresse = :adresse WHERE id = :id
            ');
            $stmt->execute([
                'id' => $id,
                'telephone' => $data['telephone'],
                'adresse' => $data['adresse'],
            ]);

            $this->pdo->commit();

            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        // ON DELETE CASCADE sur clients : supprimer l'utilisateur suffit.
        return $this->utilisateurRepository->delete($id);
    }

    private function baseQuery(): string
    {
        return "
            SELECT u.id, u.nom, u.prenom, u.email, u.actif, u.date_creation,
                   c.telephone, c.adresse
            FROM utilisateurs u
            JOIN clients c ON c.id = u.id
            WHERE 1 = 1
        ";
    }

    private function hydrate(object $row): Client
    {
        return new Client(
            (int) $row->id,
            $row->nom,
            $row->prenom,
            $row->email,
            (bool) $row->actif,
            $row->date_creation,
            $row->telephone,
            $row->adresse,
        );
    }
}
