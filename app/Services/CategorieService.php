<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;

class CategorieService
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {}

    /**
     * Créer une catégorie.
     */
    public function create(array $data): int
    {
        $data = $this->prepareData($data);

        $this->validate($data);

        // Vérifier si le libellé existe déjà
        if (
            $this->categorieRepository
                ->findByLibelle($data['libelle']) !== null
        ) {
            throw new ValidationException(
                'Cette catégorie existe déjà.',
                [
                    'libelle' => 'Cette catégorie existe déjà.'
                ]
            );
        }

        return $this->categorieRepository->create($data);
    }

    /**
     * Modifier une catégorie.
     */
    public function update(int $id, array $data): bool
    {
        if ($id <= 0) {
            throw new ValidationException(
                'Catégorie invalide.',
                [
                    'id' => 'Identifiant de la catégorie invalide.'
                ]
            );
        }

        // Vérifier que la catégorie existe
        $categorie = $this->categorieRepository->findById($id);

        if ($categorie === null) {
            throw new ValidationException(
                'Catégorie introuvable.',
                [
                    'id' => 'La catégorie demandée n’existe pas.'
                ]
            );
        }

        $data = $this->prepareData($data);

        $this->validate($data);

        // Vérifier le doublon en excluant la catégorie actuelle
        $existante = $this->categorieRepository
            ->findByLibelle($data['libelle']);

        if (
            $existante !== null
            && (int) $existante->getId() !== $id
        ) {
            throw new ValidationException(
                'Une autre catégorie porte déjà ce libellé.',
                [
                    'libelle' =>
                        'Une autre catégorie porte déjà ce libellé.'
                ]
            );
        }

        return $this->categorieRepository->update($id, $data);
    }

    /**
     * Supprimer une catégorie.
     */
    public function delete(int $id): bool
    {
        if ($id <= 0) {
            throw new ValidationException(
                'Catégorie invalide.',
                [
                    'id' => 'Identifiant de la catégorie invalide.'
                ]
            );
        }

        // Vérifier que la catégorie existe
        $categorie = $this->categorieRepository->findById($id);

        if ($categorie === null) {
            throw new ValidationException(
                'Catégorie introuvable.',
                [
                    'id' => 'La catégorie demandée n’existe pas.'
                ]
            );
        }

        return $this->categorieRepository->delete($id);
    }

    /**
     * Nettoyer les données reçues du formulaire.
     */
    private function prepareData(array $data): array
    {
        return [
            'libelle' => trim(
                (string) ($data['libelle'] ?? '')
            ),
            'description' => trim(
                (string) ($data['description'] ?? '')
            ),
        ];
    }

    /**
     * Valider les données de la catégorie.
     */
    private function validate(array $data): void
    {
        $errors = [];

        $libelle = $data['libelle'] ?? '';
        $description = $data['description'] ?? '';

        // Libellé obligatoire
        if ($libelle === '') {
            $errors['libelle'] =
                'Le libellé de la catégorie est obligatoire.';
        }

        // Maximum 60 caractères
        elseif (mb_strlen($libelle) > 60) {
            $errors['libelle'] =
                'Le libellé ne doit pas dépasser 60 caractères.';
        }

        // Description facultative, maximum 255 caractères
        if (mb_strlen($description) > 255) {
            $errors['description'] =
                'La description ne doit pas dépasser 255 caractères.';
        }

        if (!empty($errors)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs du formulaire.',
                $errors
            );
        }
    }
}