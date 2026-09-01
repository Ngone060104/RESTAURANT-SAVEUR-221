<?php

namespace App\Core;

use App\Exceptions\NotFoundException;

/**
 * Rendu centralisé des vues. Extrait dans sa propre classe (plutôt que
 * de vivre uniquement dans Controller) car les Middleware ont aussi
 * besoin d'afficher une page (403) sans être des contrôleurs.
 */
class View
{
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new NotFoundException("Vue '{$view}' introuvable.");
        }

        require $viewFile;
    }
}
