<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;
use App\Repositories\ProduitRepository;

class ProduitService
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private CategorieRepository $categorieRepository,
        private CloudinaryService $cloudinaryService,
    ) {}

    /**
     * Créer un produit.
     *
     * @param array $data Données POST
     * @param array|null $image Fichier provenant de $_FILES['image']
     */
    public function create(
        array $data,
        ?array $image = null
    ): int {
        $data = $this->normaliser($data);

        // Image obligatoire à la création
        if ($this->fichierSelectionne($image)) {
            $data['image'] = $this->cloudinaryService
                ->uploadImage($image);
        } else {
            $data['image'] = null;
        }

        $this->validate($data, true);

        return $this->produitRepository->create(
            $this->withStatutDerive($data)
        );
    }

    /**
     * Modifier un produit.
     *
     * Si aucune nouvelle image n'est sélectionnée,
     * l'ancienne image Cloudinary est conservée.
     */
    public function update(
        int $id,
        array $data,
        ?array $image = null
    ): bool {
        if ($id <= 0) {
            throw new ValidationException(
                'Produit invalide.',
                [
                    'id' => 'Identifiant du produit invalide.',
                ]
            );
        }

        $produit = $this->produitRepository
            ->findProduitById($id);

        if ($produit === null) {
            throw new ValidationException(
                'Produit introuvable.',
                [
                    'id' => 'Le produit demandé n’existe pas.',
                ]
            );
        }

        $data = $this->normaliser($data);

        // On conserve l'ancienne image par défaut
        $data['image'] = $produit->getImage();

        // Si une nouvelle image est envoyée, elle remplace l'ancienne
        if ($this->fichierSelectionne($image)) {
            $data['image'] = $this->cloudinaryService
                ->uploadImage($image);
        }

        // Image non obligatoire en modification
        $this->validate($data, false);

        return $this->produitRepository->update(
            $id,
            $this->withStatutDerive($data)
        );
    }
    /**
     * Approvisionnement du stock.
     */
    public function approvisionner(
        int $id,
        int $quantite
    ): bool {
        if ($quantite <= 0) {
            throw new ValidationException(
                'La quantité approvisionnée doit être positive.'
            );
        }

        $produit = $this->produitRepository
            ->findProduitById($id);

        if ($produit === null) {
            throw new ValidationException(
                'Produit introuvable.'
            );
        }

        return $this->produitRepository->update(
            $id,
            $this->withStatutDerive([
                'nom' => $produit->getNom(),
                'description' => $produit->getDescription(),
                'prix' => $produit->getPrix(),
                'stock' => $produit->getStock() + $quantite,
                'image' => $produit->getImage(),
                'categorie_id' => $produit->getCategorieId(),
            ])
        );
    }

    /**
     * Supprimer un produit.
     */
    public function delete(int $id): bool
    {
        if ($id <= 0) {
            throw new ValidationException(
                'Produit invalide.'
            );
        }

        if (
            $this->produitRepository
            ->findProduitById($id) === null
        ) {
            throw new ValidationException(
                'Produit introuvable.'
            );
        }

        return $this->produitRepository->delete($id);
    }

    /**
     * Vérifie si un fichier a réellement été sélectionné.
     */
    private function fichierSelectionne(
        ?array $image
    ): bool {
        return is_array($image)
            && isset($image['error'])
            && $image['error'] !== UPLOAD_ERR_NO_FILE;
    }

    /**
     * Normalisation des données.
     */
    private function normaliser(array $data): array
    {
        return [
            'nom' => trim((string) ($data['nom'] ?? '')),
            'description' => trim(
                (string) ($data['description'] ?? '')
            ),
            'prix' => $data['prix'] ?? '',
            'stock' => $data['stock'] ?? '',
            'categorie_id' => $data['categorie_id'] ?? '',
            'image' => $data['image'] ?? null,
        ];
    }

    /**
     * Déduit automatiquement le statut à partir du stock.
     */
    private function withStatutDerive(
        array $data
    ): array {
        $data['statut'] =
            ((int) $data['stock']) === 0
            ? 'en_rupture'
            : 'disponible';

        return $data;
    }

    /**
     * Validation métier.
     */
    private function validate(
        array $data,
        bool $imageObligatoire = false
    ): void {
        $erreurs = [];

        // =========================
        // NOM
        // =========================
        if (
            !isset($data['nom'])
            || trim((string) $data['nom']) === ''
        ) {
            $erreurs['nom'] = 'Le champ nom est obligatoire.';
        }

        // =========================
        // DESCRIPTION
        // =========================
        if (
            !isset($data['description'])
            || trim((string) $data['description']) === ''
        ) {
            $erreurs['description'] =
                'Le champ description est obligatoire.';
        }

        // =========================
        // PRIX
        // =========================
        if (
            !isset($data['prix'])
            || $data['prix'] === ''
        ) {
            $erreurs['prix'] = 'Le champ prix est obligatoire.';
        } elseif (!is_numeric($data['prix'])) {
            $erreurs['prix'] =
                'Le prix doit être un nombre valide.';
        } elseif ((float) $data['prix'] < 0) {
            $erreurs['prix'] =
                'Le prix ne peut pas être négatif.';
        }

        // =========================
        // STOCK
        // =========================
        if (
            !isset($data['stock'])
            || trim((string) $data['stock']) === ''
        ) {
            $erreurs['stock'] =
                'Le champ stock est obligatoire.';
        } elseif (
            !ctype_digit((string) $data['stock'])
        ) {
            $erreurs['stock'] =
                'Le stock doit être un nombre entier positif ou nul.';
        }

        // =========================
        // CATÉGORIE
        // =========================
        if (
            !isset($data['categorie_id'])
            || $data['categorie_id'] === ''
        ) {
            $erreurs['categorie_id'] =
                'Le champ catégorie est obligatoire.';
        } elseif (
            $this->categorieRepository
            ->findById((int) $data['categorie_id']) === null
        ) {
            $erreurs['categorie_id'] =
                'La catégorie sélectionnée est inconnue.';
        }

        // =========================
        // IMAGE
        // =========================
        if (
            $imageObligatoire
            && (
                !isset($data['image'])
                || $data['image'] === null
                || trim((string) $data['image']) === ''
            )
        ) {
            $erreurs['image'] =
                'L’image du produit est obligatoire.';
        }

        // =========================
        // ERREURS
        // =========================
        if (!empty($erreurs)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs du formulaire.',
                $erreurs
            );
        }
    }
}
