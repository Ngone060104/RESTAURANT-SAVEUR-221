<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\ClientRepository;
use App\Services\AuthService;
use App\Services\ProfilService;

/**
 * Section "Client -> Profil". Protégé par ClientMiddleware.
 */
class ProfilController extends Controller
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ProfilService $profilService,
    ) {
    }

    public function show(): void
    {
        $clientId = AuthService::currentUser()['id'];

        $this->view('profil/show', [
            'client' => $this->clientRepository->findClientById($clientId),
        ]);
    }

    public function update(): void
    {
        try {
            $clientId = AuthService::currentUser()['id'];
            $this->profilService->modifierInfos($clientId, $_POST);
            $this->redirect('/profil');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }

    public function changerMotDePasse(): void
    {
        try {
            $clientId = AuthService::currentUser()['id'];
            $ancien = $_POST['ancien_mdp'] ?? '';
            $nouveau = $_POST['nouveau_mdp'] ?? '';

            $this->profilService->changerMotDePasse($clientId, $ancien, $nouveau);
            $this->redirect('/profil');
        } catch (ValidationException $e) {
            http_response_code(422);
            echo $e->getMessage();
        }
    }
}
