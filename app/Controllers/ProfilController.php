<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\ClientRepository;
use App\Services\AuthService;
use App\Services\ProfilService;

class ProfilController extends Controller
{
    public function __construct(
        private ClientRepository $clientRepository,
        private ProfilService $profilService,
    ) {
    }

    /**
     * Afficher le profil.
     */
    public function show(): void
    {
        $utilisateur = AuthService::currentUser();

        if ($utilisateur === null) {
            $this->redirect('/connexion');
            return;
        }

        $clientId = (int) $utilisateur['id'];

        $this->view('profil/show', [
            'client' => $this->clientRepository->findClientById($clientId),
            'utilisateur' => $utilisateur,
            'erreurs' => [],
            'form' => [],
            'success' => $_GET['success'] ?? null,
        ]);
    }

    /**
     * Modifier les informations personnelles.
     */
    public function update(): void
    {
        $utilisateur = AuthService::currentUser();

        if ($utilisateur === null) {
            $this->redirect('/connexion');
            return;
        }

        $clientId = (int) $utilisateur['id'];

        try {
            $this->profilService->modifierInfos(
                $clientId,
                $_POST
            );

            $this->redirect('/profil?success=infos');
        } catch (ValidationException $e) {
            $client = $this->clientRepository->findClientById(
                $clientId
            );

            $this->view('profil/show', [
                'client' => $client,
                'utilisateur' => $utilisateur,

                /*
                | IMPORTANT :
                | On transmet les erreurs champ par champ.
                */
                'erreurs' => $e->getErrors(),

                /*
                | On garde ce que l'utilisateur avait saisi.
                */
                'form' => $_POST,

                'success' => null,
            ]);
        }
    }

    /**
     * Modifier le mot de passe.
     */
    public function changerMotDePasse(): void
    {
        $utilisateur = AuthService::currentUser();

        if ($utilisateur === null) {
            $this->redirect('/connexion');
            return;
        }

        $clientId = (int) $utilisateur['id'];

        try {
            $ancienMdp = $_POST['ancien_mdp'] ?? '';
            $nouveauMdp = $_POST['nouveau_mdp'] ?? '';
            $confirmationMdp =
                $_POST['confirmation_mdp'] ?? '';

            $this->profilService->changerMotDePasse(
                $clientId,
                $ancienMdp,
                $nouveauMdp,
                $confirmationMdp
            );

            $this->redirect('/profil?success=password');
        } catch (ValidationException $e) {
            $client = $this->clientRepository->findClientById(
                $clientId
            );

            $this->view('profil/show', [
                'client' => $client,
                'utilisateur' => $utilisateur,
                'erreurs' => $e->getErrors(),
                'form' => [],
                'success' => null,
            ]);
        }
    }
}