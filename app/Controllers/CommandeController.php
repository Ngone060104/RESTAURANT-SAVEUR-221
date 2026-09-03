<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Services\AuthService;
use App\Services\CommandeService;

/**
 * Section "Client -> Commande / Suivi de commande / Historique client".
 * Toutes les routes sont protégées par ClientMiddleware.
 */
class CommandeController extends Controller
{
    public function __construct(private CommandeService $commandeService)
    {
    }

    public function valider(): void
    {
        try {
            $clientId = AuthService::currentUser()['id'];
            $commandeId = $this->commandeService->validerPanier($clientId);

            $this->redirect("/commande?id={$commandeId}");
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function show(): void
    {
        try {
            $clientId = AuthService::currentUser()['id'];
            $commandeId = (int) ($_GET['id'] ?? 0);

            $detail = $this->commandeService->getDetail($commandeId, $clientId);

            $this->view('commandes/show', $detail);
        } catch (AuthException $e) {
            http_response_code(403);
            View::render('errors/403', ['message' => $e->getMessage()], null);
        } catch (ValidationException $e) {
            http_response_code(404);
            View::render('errors/404', [], null);
        }
    }

    public function historique(): void
    {
        $clientId = AuthService::currentUser()['id'];

        $this->view('commandes/historique', [
            'commandes' => $this->commandeService->getHistoriqueClient($clientId),
        ]);
    }
}
