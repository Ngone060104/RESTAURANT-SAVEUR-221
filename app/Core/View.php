<?php

namespace App\Core;

use App\Exceptions\NotFoundException;

/**
 * Rendu centralisé des vues, avec support des layouts (navbar + contenu
 * + footer côté public, sidebar côté dashboard) et des partiels
 * (fragments réutilisables : navbar, footer, sidebar...).
 */
class View
{
    /**
     * Rend une vue. Si $layout est fourni, la sortie de la vue est
     * capturée puis injectée dans $content à l'intérieur du layout -
     * le layout peut donc écrire <?= $content ?> où il veut (entre sa
     * navbar et son footer, par exemple).
     */
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/app'): void
    {
        extract($data);

        $viewFile = self::path($view);

        if (!file_exists($viewFile)) {
            throw new NotFoundException("Vue '{$view}' introuvable.");
        }

        if ($layout === null) {
            require $viewFile;

            return;
        }

        $layoutFile = self::path($layout);

        if (!file_exists($layoutFile)) {
            throw new NotFoundException("Layout '{$layout}' introuvable.");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    /**
     * Rend un fragment (navbar, footer, sidebar...) sans layout ni
     * capture de sortie - écrit directement dans le buffer courant.
     */
    public static function partial(string $partial, array $data = []): void
    {
        extract($data);

        $file = __DIR__ . '/../Views/partials/' . $partial . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }

    private static function path(string $view): string
    {
        return __DIR__ . '/../Views/' . $view . '.php';
    }
}
