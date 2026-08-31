<?php

namespace App\Services;

/**
 * Encapsule le hachage bcrypt (atelier 18). Compatible avec les hash
 * déjà présents dans database.sql (format $2a$... généré côté Java).
 */
class PasswordHasher
{
    public function hash(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_BCRYPT);
    }

    public function verify(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}
