<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Exceptions\AuthException;
use App\Exceptions\ValidationException;
use App\Services\AuthService;
use App\Services\AvisService;

/**
 * Section "Client -> Avis" : laisser un avis après une commande RETIREE.
 * Protégé par ClientMiddleware.
 */
class AvisController extends Controller
{
    public function __construct(private AvisService $avisService)
    {
    }

    public function store(): void
    {
        try {
            $clientId = AuthService::currentUser()['id'];
            $commandeId = (int) ($_POST['commande_id'] ?? 0);
            $note = (int) ($_POST['note'] ?? 0);
            $commentaire = trim($_POST['commentaire'] ?? '') ?: null;

            $this->avisService->laisserAvis($clientId, $commandeId, $note, $commentaire);
            $this->redirect("/commande?id={$commandeId}");
        } catch (AuthException $e) {
            http_response_code(403);
            View::render('errors/403', ['message' => $e->getMessage()]);
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }
}
