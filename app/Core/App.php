<?php

namespace App\Core;

use App\Exceptions\NotFoundException;
use Throwable;

/**
 * Point d'entrée applicatif : construit le container, charge les routes
 * puis dispatch la requête courante. Sert de "Front Controller".
 */
class App
{
    private Router $router;

    public function __construct(private Container $container)
    {
        $this->router = new Router($this->container);
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        try {
            $this->router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ?? '/');
        } catch (NotFoundException $e) {
            $this->afficherErreur(404, 'errors/404');
        } catch (Throwable $e) {
            http_response_code(500);

            echo '<pre>';
            echo htmlspecialchars(
                $e->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            );
            echo "\n\n";
            echo htmlspecialchars(
                $e->getFile(),
                ENT_QUOTES,
                'UTF-8'
            );
            echo ':';
            echo $e->getLine();
            echo "\n\n";
            echo htmlspecialchars(
                $e->getTraceAsString(),
                ENT_QUOTES,
                'UTF-8'
            );
            echo '</pre>';
        }
    }

    /**
     * Si l'erreur survient pendant le rendu d'une vue (donc à l'intérieur
     * d'un ob_start() de View::render()), ce tampon reste ouvert avec du
     * contenu partiel dedans. Sans ce nettoyage, ce contenu partiel finit
     * par fuiter dans la page d'erreur (ou provoque un "headers already
     * sent"). On vide donc TOUS les tampons ouverts avant d'afficher quoi
     * que ce soit.
     */
    private function afficherErreur(int $code, string $vue): void
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code($code);
        // layout: null - errors/404.php et errors/500.php sont déjà des
        // documents HTML complets et autonomes (leur propre DOCTYPE/head/
        // body), pas question de les emboîter dans le layout public.
        View::render($vue, [], null);
    }
}
