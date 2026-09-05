<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\CategorieRepository;

class CategorieService
{
    public function __construct(
        private CategorieRepository $categorieRepository
    ) {
    }

    public function create(array $data): int
    {
        $data = $this->prepareData($data);

        $this->validate($data);

        // Vérifier si le libellé existe déjà
        if ($this->categorieRepository->findByLibelle($data['libelle']) !== null) {
            throw new ValidationException(
                'Cette catégorie existe déjà.',
                [
                    'libelle' => 'Cette catégorie existe déjà.'
                ]
            );
        }

        return $this->categorieRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->prepareData($data);

        $this->validate($data);

        // Vérifier le doublon en excluant la catégorie actuelle
        $existante = $this->categorieRepository->findByLibelle($data['libelle']);

        if ($existante !== null && (int) $existante->id !== $id) {
            throw new ValidationException(
                'Une autre catégorie porte déjà ce libellé.',
                [
                    'libelle' => 'Une autre catégorie porte déjà ce libellé.'
                ]
            );
        }

        return $this->categorieRepository->update($id, $data);
    }

    /**
     * Supprimer une catégorie.
     *
     * Le repository gère déjà le cas où la catégorie
     * contient encore des produits.
     */
    public function delete(int $id): bool
    {
        return $this->categorieRepository->delete($id);
    }

    /**
     * Nettoyer les données reçues du formulaire.
     */
    private function prepareData(array $data): array
    {
        return [
            'libelle' => trim($data['libelle'] ?? ''),
            'description' => trim($data['description'] ?? ''),
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
            $errors['libelle'] = 'Le libellé de la catégorie est obligatoire.';
        }

        // Maximum 60 caractères
        elseif (mb_strlen($libelle) > 60) {
            $errors['libelle'] = 'Le libellé ne doit pas dépasser 60 caractères.';
        }

        // Description facultative, maximum 255 caractères
        if (mb_strlen($description) > 255) {
            $errors['description'] = 'La description ne doit pas dépasser 255 caractères.';
        }

        if (!empty($errors)) {
            throw new ValidationException(
                'Veuillez corriger les erreurs du formulaire.',
                $errors
            );
        }
    }
}