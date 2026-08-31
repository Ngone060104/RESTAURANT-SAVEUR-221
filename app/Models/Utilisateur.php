<?php

namespace App\Models;

/**
 * Représente une ligne de la table `utilisateurs` (classe mère du
 * diagramme de classes : personnel interne ET clients en héritent).
 * Encapsulation : les propriétés sont privées, on passe par des getters.
 */
class Utilisateur
{
    public function __construct(
        private int $id,
        private string $nom,
        private string $prenom,
        private string $email,
        private bool $actif,
        private string $role, // libellé du rôle : ADMIN, GERANT ou CLIENT
        private ?string $dateCreation = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getNomComplet(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    public function isGerant(): bool
    {
        return $this->role === 'GERANT';
    }

    public function isClient(): bool
    {
        return $this->role === 'CLIENT';
    }
}
