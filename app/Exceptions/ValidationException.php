<?php

namespace App\Exceptions;

/**
 * Levée quand une règle métier ou de validation n'est pas respectée
 * (ex: stock insuffisant, email déjà utilisé, panier vide...).
 */
class ValidationException extends AppException
{
}
