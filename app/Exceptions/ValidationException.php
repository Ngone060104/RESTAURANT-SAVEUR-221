<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct(
        string $message = '',
        private array $errors = []
    ) {
        parent::__construct($message);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}