<?php

namespace App\Exceptions;

/**
 * Levée pour tout échec d'authentification ou d'autorisation
 * (identifiants invalides, compte désactivé, accès refusé...).
 */
class AuthException extends AppException
{
}
