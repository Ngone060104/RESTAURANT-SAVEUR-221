<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Utilisateur;
use PDO;

/**
 * Accès à la table `utilisateurs` jointe à `roles`.
 * Sert de base à l'authentification (tous rôles confondus) et à la
 * gestion des comptes internes par l'administrateur.
 */
class UtilisateurRepository implements RepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT u.*, r.libelle AS role
            FROM utilisateurs u
            JOIN roles r ON r.id = u.role_id
            ORDER BY u.id
        ");

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->pdo->prepare("
        SELECT u.*, r.libelle AS role
        FROM utilisateurs u
        JOIN roles r ON r.id = u.role_id
        WHERE u.id = :id
    ");

        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function findByIdHydrate(int $id): ?Utilisateur
{
    $stmt = $this->pdo->prepare("
        SELECT u.*, r.libelle AS role
        FROM utilisateurs u
        JOIN roles r ON r.id = u.role_id
        WHERE u.id = :id
    ");

    $stmt->execute([
        'id' => $id,
    ]);

    $row = $stmt->fetch();

    return $row ? $this->hydrate($row) : null;
}

    /**
     * Recherche brute par email, mot de passe (haché) inclus.
     * Utilisée uniquement par AuthService pour vérifier les identifiants.
     */
    public function findByEmailWithPassword(string $email): ?object
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, r.libelle AS role
            FROM utilisateurs u
            JOIN roles r ON r.id = u.role_id
            WHERE u.email = :email
        ");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function emailExists(
        string $email,
        ?int $excludeId = null
    ): bool {
        if ($excludeId === null) {
            $stmt = $this->pdo->prepare('
            SELECT 1
            FROM utilisateurs
            WHERE email = :email
            LIMIT 1
        ');

            $stmt->execute([
                'email' => $email,
            ]);
        } else {
            $stmt = $this->pdo->prepare('
            SELECT 1
            FROM utilisateurs
            WHERE email = :email
              AND id <> :exclude_id
            LIMIT 1
        ');

            $stmt->execute([
                'email' => $email,
                'exclude_id' => $excludeId,
            ]);
        }

        return (bool) $stmt->fetchColumn();
    }

    public function findRoleIdByLibelle(string $libelle): ?int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM roles WHERE libelle = :libelle');
        $stmt->execute(['libelle' => $libelle]);
        $id = $stmt->fetchColumn();

        return $id === false ? null : (int) $id;
    }


    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO utilisateurs (nom, prenom, email, mdp, actif, role_id)
            VALUES (:nom, :prenom, :email, :mdp, :actif, :role_id)
            RETURNING id
        ");
        $stmt->execute([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'mdp' => $data['mdp'],
            'actif' => $data['actif'] ?? true,
            'role_id' => $data['role_id'],
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
        UPDATE utilisateurs
        SET nom = :nom,
            prenom = :prenom,
            email = :email,
            actif = :actif,
            role_id = :role_id
        WHERE id = :id
    ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nom', $data['nom'], PDO::PARAM_STR);
        $stmt->bindValue(':prenom', $data['prenom'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(
            ':actif',
            (bool) $data['actif'],
            PDO::PARAM_BOOL
        );
        $stmt->bindValue(
            ':role_id',
            (int) $data['role_id'],
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }


    public function updateMotDePasse(int $id, string $mdpHache): bool
    {
        $stmt = $this->pdo->prepare('UPDATE utilisateurs SET mdp = :mdp WHERE id = :id');

        return $stmt->execute(['id' => $id, 'mdp' => $mdpHache]);
    }

    /**
     * Utilisateurs internes uniquement (ADMIN/GERANT), sans les clients -
     * section "ESPACE ADMINISTRATEUR -> CRUD utilisateurs".
     */
    public function findInternes(): array
    {
        $stmt = $this->pdo->query("
            SELECT u.*, r.libelle AS role
            FROM utilisateurs u
            JOIN roles r ON r.id = u.role_id
            WHERE r.libelle IN ('ADMIN', 'GERANT')
            ORDER BY u.id
        ");

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function searchInternes(string $terme): array
    {
        $stmt = $this->pdo->prepare("
            SELECT u.*, r.libelle AS role
            FROM utilisateurs u
            JOIN roles r ON r.id = u.role_id
            WHERE r.libelle IN ('ADMIN', 'GERANT')
              AND (u.nom ILIKE :terme OR u.prenom ILIKE :terme OR u.email ILIKE :terme)
            ORDER BY u.id
        ");
        $stmt->execute(['terme' => "%{$terme}%"]);

        return array_map([$this, 'hydrate'], $stmt->fetchAll());
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM utilisateurs WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(object $row): Utilisateur
    {
        return new Utilisateur(
            (int) $row->id,
            $row->nom,
            $row->prenom,
            $row->email,
            (bool) $row->actif,
            $row->role,
            $row->date_creation,
        );
    }

    public function updateActif(int $id, bool $actif): bool
{
    $stmt = $this->pdo->prepare("
        UPDATE utilisateurs
        SET actif = :actif
        WHERE id = :id
    ");

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':actif', $actif, PDO::PARAM_BOOL);

    return $stmt->execute();
}
}
