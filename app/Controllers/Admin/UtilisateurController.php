<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Exceptions\ValidationException;
use App\Repositories\UtilisateurRepository;
use App\Services\UtilisateurService;

class UtilisateurController extends Controller
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private UtilisateurService $utilisateurService,
    ) {}

    /**
     * Liste des utilisateurs internes.
     */
    public function index(): void
    {
        $terme = trim($_GET['q'] ?? '');

        $utilisateurs = $terme !== ''
            ? $this->utilisateurRepository->searchInternes($terme)
            : $this->utilisateurRepository->findInternes();

        $this->view(
            'admin/utilisateurs/index',
            [
                'utilisateurs' => $utilisateurs,
                'termeRecherche' => $terme,
            ],
            'layouts/gerant'
        );
    }

    /**
     * Recherche sans query string.
     *
     * Exemple :
     * /admin/utilisateurs/recherche/Diop
     */
    public function recherche(string $terme): void
    {
        $terme = trim(urldecode($terme));

        if ($terme === '') {
            $this->redirect('/admin/utilisateurs');
            return;
        }

        $this->view(
            'admin/utilisateurs/index',
            [
                'utilisateurs' =>
                $this->utilisateurRepository->searchInternes($terme),
                'termeRecherche' => $terme,
            ],
            'layouts/gerant'
        );
    }

    /**
     * Créer un utilisateur interne.
     */
    public function store(): void
    {
        $donnees = [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'mdp' => $_POST['mdp'] ?? '',
            'role_id' => (int) ($_POST['role_id'] ?? 0),
        ];

        try {
            $this->utilisateurService->creer($donnees);

            $this->redirect('/admin/utilisateurs');
            return;
        } catch (ValidationException $e) {

            http_response_code(422);

            $this->view(
                'admin/utilisateurs/index',
                [
                    'utilisateurs' =>
                    $this->utilisateurRepository->findInternes(),

                    'termeRecherche' => '',

                    'erreurs' => $e->getErrors(),

                    'typeFormulaire' => 'ajout',

                    'donneesFormulaire' => $donnees,

                    'ouvrirModal' => true,
                ],
                'layouts/gerant'
            );

            return;
        }
    }

    /**
     * Modifier un utilisateur interne.
     */
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->utilisateurService->modifier(
                $id,
                $_POST
            );

            $this->redirect('/admin/utilisateurs');
            return;
        } catch (ValidationException $e) {
            http_response_code(422);

            $utilisateurEdition =
                $this->utilisateurRepository->findByIdHydrate($id);

            $this->view(
                'admin/utilisateurs/index',
                [
                    'utilisateurs' =>
                    $this->utilisateurRepository->findInternes(),

                    'termeRecherche' => '',

                    'erreurs' =>
                    $e->getErrors(),

                    'typeFormulaire' =>
                    'modification',

                    'donneesFormulaire' =>
                    $_POST,

                    'utilisateurEdition' =>
                    $utilisateurEdition,

                    'ouvrirModal' =>
                    true,
                ],
                'layouts/gerant'
            );

            return;
        }
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->utilisateurService->supprimer($id);

            $this->redirect('/admin/utilisateurs');
            return;
        } catch (ValidationException $e) {

            http_response_code(422);

            $this->view(
                'admin/utilisateurs/index',
                [
                    'utilisateurs' =>
                    $this->utilisateurRepository->findInternes(),

                    'erreurs' => [
                        'general' => $e->getMessage(),
                    ],

                    'ouvrirModal' => false,
                ],
                'layouts/gerant'
            );

            return;
        }
    }

    /**
     * Activer un utilisateur.
     */
    public function activer(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->utilisateurService->activer($id);

            $this->redirect('/admin/utilisateurs');
            return;
        } catch (ValidationException $e) {

            http_response_code(422);

            $this->view(
                'admin/utilisateurs/index',
                [
                    'utilisateurs' =>
                    $this->utilisateurRepository->findInternes(),

                    'erreurs' => [
                        'general' => $e->getMessage(),
                    ],

                    'ouvrirModal' => false,
                ],
                'layouts/gerant'
            );

            return;
        }
    }

    /**
     * Désactiver un utilisateur.
     */
    public function desactiver(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            $this->utilisateurService->desactiver($id);

            $this->redirect('/admin/utilisateurs');
            return;
        } catch (ValidationException $e) {

            http_response_code(422);

            $this->view(
                'admin/utilisateurs/index',
                [
                    'utilisateurs' =>
                    $this->utilisateurRepository->findInternes(),

                    'erreurs' => [
                        'general' => $e->getMessage(),
                    ],

                    'ouvrirModal' => false,
                ],
                'layouts/gerant'
            );

            return;
        }
    }
}
