<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Repositories\ClientRepository;
use App\Services\AuthService;
use App\Services\CommandeService;
use App\Services\PanierService;

class CommandeController extends Controller
{
    public function __construct(
        private CommandeService $commandeService,
        private ClientRepository $clientRepository,
        private PanierService $panierService,
    ) {}

    /**
     * Page :
     * Validation & Confirmation de votre commande
     *
     * Aucune commande n'est créée ici.
     */
    public function confirmation(): void
    {
        $user = AuthService::currentUser();

        if ($user === null) {
            $this->redirect('/login');
            return;
        }

        $client = $this->clientRepository->findClientById(
            (int) $user['id']
        );

        if ($client === null) {
            http_response_code(404);

            View::render(
                'errors/404',
                [],
                null
            );

            return;
        }

        $lignes = $this->panierService->getLignes();

        if (empty($lignes)) {
            $this->redirect('/panier');
            return;
        }

        $this->view('commandes/confirmation', [
            'client' => $client,
            'lignes' => $lignes,
            'total' => $this->panierService->getTotal(),
        ]);
    }

    /**
     * Création réelle de la commande.
     */
    public function valider(): void
    {
        try {
            $user = AuthService::currentUser();

            if ($user === null) {
                $this->redirect('/login');
                return;
            }

            $clientId = (int) $user['id'];

            $commandeId = $this->commandeService->validerPanier(
                $clientId
            );

            /*
             * Après création de la commande,
             * on affiche la page de succès.
             */
            $this->redirect("/commande/show/{$commandeId}");
        } catch (ValidationException $e) {
            http_response_code(422);

            echo htmlspecialchars(
                $e->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            );
        }
    }

    /**
     * Page de succès :
     * "Merci pour votre commande !"
     *
     * Cette page est différente du détail d'une commande.
     */
   public function show(int $id): void
{
    try {
        $user = AuthService::currentUser();

        if ($user === null) {
            $this->redirect('/login');
            return;
        }

        if ($id <= 0) {
            http_response_code(404);

            View::render(
                'errors/404',
                [],
                null
            );

            return;
        }

        $detail = $this->commandeService->getDetail(
            $id,
            (int) $user['id']
        );

        $this->view(
            'commandes/show',
            $detail
        );

    } catch (AuthException $e) {

        http_response_code(403);

        View::render(
            'errors/403',
            [
                'message' => $e->getMessage()
            ],
            null
        );

    } catch (ValidationException $e) {

        http_response_code(404);

        View::render(
            'errors/404',
            [],
            null
        );
    }
}

    /**
     * Détail d'une commande existante.
     *
     * URL :
     * /commande/detail/4
     *
     * Le paramètre {id} est transmis directement
     * par le Router au contrôleur.
     */
    public function detail(int $id): void
    {
        try {
            $user = AuthService::currentUser();

            if ($user === null) {
                $this->redirect('/login');
                return;
            }

            if ($id <= 0) {
                http_response_code(404);

                View::render(
                    'errors/404',
                    [],
                    null
                );

                return;
            }

            /*
             * Récupération de la commande,
             * des lignes et des paiements.
             *
             * Le service vérifie également que
             * la commande appartient bien au client connecté.
             */
            $detail = $this->commandeService->getDetail(
                $id,
                (int) $user['id']
            );

            /*
             * Récupération des informations complètes
             * du client pour la page détail.
             */
            $client = $this->clientRepository->findClientById(
                (int) $user['id']
            );

            if ($client === null) {
                http_response_code(404);

                View::render(
                    'errors/404',
                    [],
                    null
                );

                return;
            }

            $detail['client'] = $client;

            $this->view(
                'commandes/detail',
                $detail
            );
        } catch (AuthException $e) {
            http_response_code(403);

            View::render(
                'errors/403',
                [
                    'message' => $e->getMessage(),
                ],
                null
            );
        } catch (ValidationException $e) {
            http_response_code(404);

            View::render(
                'errors/404',
                [],
                null
            );
        }
    }

    /**
     * Historique des commandes du client connecté.
     */
    public function historique(): void
    {
        $user = AuthService::currentUser();

        if ($user === null) {
            $this->redirect('/login');
            return;
        }

        $this->view('commandes/historique', [
            'commandes' => $this->commandeService
                ->getHistoriqueClient(
                    (int) $user['id']
                ),
        ]);
    }

    /**
 * Suivi de l'avancement d'une commande.
 *
 * Exemple :
 * /commande/suivi/9
 */
public function suivi(int $id): void
{
    try {
        $user = AuthService::currentUser();

        if ($user === null) {
            $this->redirect('/login');
            return;
        }

        if ($id <= 0) {
            http_response_code(404);

            View::render(
                'errors/404',
                [],
                null
            );

            return;
        }

        /*
         * getDetail() vérifie également que la commande
         * appartient bien au client connecté.
         */
        $detail = $this->commandeService->getDetail(
            $id,
            (int) $user['id']
        );

        $this->view(
            'commandes/suivi',
            $detail
        );

    } catch (AuthException $e) {

        http_response_code(403);

        View::render(
            'errors/403',
            [
                'message' => $e->getMessage()
            ],
            null
        );

    } catch (ValidationException $e) {

        http_response_code(404);

        View::render(
            'errors/404',
            [],
            null
        );
    }
}

}
