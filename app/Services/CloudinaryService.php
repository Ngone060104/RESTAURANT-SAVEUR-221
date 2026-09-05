<?php

namespace App\Services;

use App\Exceptions\ValidationException;

class CloudinaryService
{
    /**
     * Upload une image vers Cloudinary.
     *
     * Le preset Cloudinary doit être configuré en "Unsigned".
     *
     * @param array $file Élément provenant de $_FILES['image']
     * @return string URL sécurisée de l'image
     */
    public function uploadImage(array $file): string
    {
        // Vérifier qu'un fichier existe
        if (
            !isset($file['error'], $file['tmp_name'])
            || $file['error'] === UPLOAD_ERR_NO_FILE
        ) {
            throw new ValidationException(
                'Aucune image sélectionnée.',
                [
                    'image' => 'Veuillez sélectionner une image.',
                ]
            );
        }

        // Vérifier l'erreur d'upload PHP
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException(
                'Erreur lors de l’envoi de l’image.',
                [
                    'image' => 'Impossible de recevoir l’image.',
                ]
            );
        }

        // Vérifier que le fichier vient bien d'un upload HTTP
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new ValidationException(
                'Fichier image invalide.',
                [
                    'image' => 'Le fichier envoyé est invalide.',
                ]
            );
        }

        // Limite : 5 Mo
        $tailleMax = 5 * 1024 * 1024;

        if (($file['size'] ?? 0) > $tailleMax) {
            throw new ValidationException(
                'Image trop volumineuse.',
                [
                    'image' => 'L’image ne doit pas dépasser 5 Mo.',
                ]
            );
        }

        // Vérification réelle du type MIME
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $typesAutorises = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif',
        ];

        if (!in_array($mime, $typesAutorises, true)) {
            throw new ValidationException(
                'Format d’image invalide.',
                [
                    'image' => 'Formats autorisés : JPG, PNG, WEBP ou GIF.',
                ]
            );
        }

        // Charger la configuration
        $config = require __DIR__ . '/../../config/config.php';

        $cloudName = $config['cloudinary']['cloud_name'];
        $uploadPreset = $config['cloudinary']['upload_preset'];
        $folder = $config['cloudinary']['folder'];

        // URL Cloudinary
        $url = 'https://api.cloudinary.com/v1_1/'
            . rawurlencode($cloudName)
            . '/image/upload';

        // Données envoyées à Cloudinary
        $postData = [
            'file' => new \CURLFile(
                $file['tmp_name'],
                $mime,
                $file['name']
            ),
            'upload_preset' => $uploadPreset,
            'folder' => $folder,
        ];

        // Initialiser cURL
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]);

        // Envoyer à Cloudinary
        $response = curl_exec($ch);

        // Erreur cURL
        if ($response === false) {
            $error = curl_error($ch);

            curl_close($ch);

            throw new ValidationException(
                'Impossible de contacter Cloudinary.',
                [
                    'image' => 'Erreur cURL : ' . $error,
                ]
            );
        }

        // Code HTTP
        $httpCode = (int) curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        curl_close($ch);

        // DEBUG TEMPORAIRE
        error_log('===== CLOUDINARY =====');
        error_log('HTTP CODE : ' . $httpCode);
        error_log('CLOUD NAME : ' . $cloudName);
        error_log('UPLOAD PRESET : ' . $uploadPreset);
        error_log('FOLDER : ' . $folder);
        error_log('URL : ' . $url);
        error_log('RESPONSE : ' . $response);
        error_log('======================');

        // Décoder la réponse
        $result = json_decode($response, true);

        // Vérifier la réponse Cloudinary
        if (
            $httpCode < 200
            || $httpCode >= 300
            || !is_array($result)
            || empty($result['secure_url'])
        ) {
            $message = 'L’image n’a pas pu être envoyée vers Cloudinary.';

            if (
                is_array($result)
                && isset($result['error']['message'])
            ) {
                $message = $result['error']['message'];
            }

            throw new ValidationException(
                $message,
                [
                    'image' => $message,
                ]
            );
        }

        // Retourner l'URL Cloudinary
        return $result['secure_url'];
    }
}