<?php

namespace App\Interfaces;

/**
 * Contrat que doit respecter tout middleware (atelier 07 - interfaces).
 * Un middleware s'exécute avant le contrôleur : auth, rôle, etc.
 */
interface MiddlewareInterface
{
    /**
     * @return bool true si la requête peut continuer, false si elle a été bloquée
     *              (le middleware est alors responsable de la redirection/réponse).
     */
    public function handle(): bool;
}
