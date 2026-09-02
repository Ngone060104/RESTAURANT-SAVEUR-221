<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Connexion PDO à PostgreSQL, sous forme de Singleton classique
 * (constructeur privé + instance unique via getInstance()) : toute
 * l'application partage la même connexion, jamais deux.
 */
class Database
{
    private static ?Database $instance = null;

    private PDO $connection;

    /**
     * Privé : personne d'autre que getInstance() ne peut créer
     * une Database (c'est ça, le coeur du pattern Singleton).
     */
    private function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $db = $config['db'];

        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $db['host'],
            $db['port'],
            $db['name']
        );

        try {
            $this->connection = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ ,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Connexion à la base de données impossible : ' . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    // Empêche aussi le clonage et la désérialisation de l'instance
    // (sinon on pourrait contourner le Singleton par ces deux biais).
    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception('Impossible de désérialiser un Singleton.');
    }
}
