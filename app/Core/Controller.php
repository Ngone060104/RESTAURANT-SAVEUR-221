<?php

namespace App\Core;

use App\Exceptions\NotFoundException;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new NotFoundException("Vue '{$view}' introuvable.");
        }

        require $viewFile;
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}