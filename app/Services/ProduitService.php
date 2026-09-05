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
    ) {}

    public function create(array $data): int
    {
        $data = $this->normaliser($data);

        $this->validate($data);

        return $this->produitRepository->create(
            $this->withStatutDerive($data)
        );
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->normaliser($data);

        $this->validate($data);

        return $this->produitRepository->update(
            $id,
            $this->withStatutDerive($data)
        );
    }

    public function approvisionner(int $id, int $quantite): bool
    {
        if ($quantite <= 0) {
            throw new ValidationException(
                'Veuillez corriger les erreurs du formulaire.',
                [
                    'quantite' => 'La quantité approvisionnée doit être supérieure à 0.'
                ]
            );
        }

        $produit = $this->produitRepository->findProduitById($id);

        if ($produit === null) {
            throw new ValidationException('Produit introuvable.');
        }

        $stock = $produit->getStock() + $quantite;

        return $this->produitRepository->update(
            $id,
            $this->withStatutDerive([
                'nom' => $produit->getNom(),
                'description' => $produit->getDescription(),
                'prix' => $produit->getPrix(),
                'stock' => $stock,
                'image' => $produit->getImage(),
                'categorie_id' => $produit->getCategorieId(),
            ])
        );
    }

    public function delete(int $id): bool
    {
        return $this->produitRepository->delete($id);
    }

    /**
     * Nettoyage des données reçues du formulaire.
     */
    private function normaliser(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        return $data;
    }

    /**
     * Détermine automatiquement le statut du produit
     * à partir du stock.
     */
    private function withStatutDerive(array $data): array
    {
        $data['statut'] =
            ((int) ($data['stock'] ?? 0)) === 0
                ? 'en_rupture'
                : 'disponible';

        return $data;
    }

    /**
     * Validation complète du formulaire produit.
     *
     * Les erreurs sont retournées champ par champ
     * afin de pouvoir les afficher directement sous chaque input.
     */
    private function validate(array $data): void
    {
        $errors = [];

        /*
         * NOM
         */
        if (!isset($data['nom']) || $data['nom'] === '') {
            $errors['nom'] = 'Le nom du produit est obligatoire.';
        } elseif (mb_strlen($data['nom']) > 100) {
            $errors['nom'] =
                'Le nom du produit ne doit pas dépasser 100 caractères.';
        }

        /*
         * DESCRIPTION
         */
        if (
            isset($data['description'])
            && $data['description'] !== ''
            && mb_strlen($data['description']) > 255
        ) {
            $errors['description'] =
                "La description ne doit pas dépasser 255 caractères.";
        }

        /*
         * PRIX
         */
        if (!isset($data['prix']) || $data['prix'] === '') {
            $errors['prix'] = 'Le prix est obligatoire.';
        } elseif (!is_numeric($data['prix'])) {
            $errors['prix'] = 'Le prix doit être un nombre valide.';
        } elseif ((float) $data['prix'] < 0) {
            $errors['prix'] = 'Le prix ne peut pas être négatif.';
        }

        /*
         * STOCK
         */
        if (!isset($data['stock']) || $data['stock'] === '') {
            $errors['stock'] = 'Le stock est obligatoire.';
        } elseif (
            filter_var(
                $data['stock'],
                FILTER_VALIDATE_INT
            ) === false
        ) {
            $errors['stock'] =
                'Le stock doit être un nombre entier.';
        } elseif ((int) $data['stock'] < 0) {
            $errors['stock'] =
                'Le stock ne peut pas être négatif.';
        }

        /*
         * CATÉGORIE
         */
        if (
            !isset($data['categorie_id'])
            || $data['categorie_id'] === ''
        ) {
            $errors['categorie_id'] =
                'La catégorie est obligatoire.';
        } elseif (
            filter_var(
                $data['categorie_id'],
                FILTER_VALIDATE_INT
            ) === false
        ) {
            $errors['categorie_id'] =
                'La catégorie sélectionnée est invalide.';
        } elseif (
            $this->categorieRepository->findById(
                (int) $data['categorie_id']
            ) === null
        ) {
            $errors['categorie_id'] =
                "La catégorie sélectionnée n'existe pas.";
        }

        /*
         * IMAGE
         */
        if (
            isset($data['image'])
            && $data['image'] !== ''
            && mb_strlen($data['image']) > 255
        ) {
            $errors['image'] =
                "Le chemin de l'image ne doit pas dépasser 255 caractères.";
        }

        /*
         * S'IL Y A DES ERREURS
         */
        if (!empty($errors)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs du formulaire.',
                $errors
            );
        }
    }
}