<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use PDO;

class DatabaseConnection
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $_ENV['DATA_BASE_HOST']    ?? 'localhost',
            $_ENV['DATA_BASE_DB']      ?? 'blog_react_php',
            $_ENV['DATA_BASE_CHARSET'] ?? 'utf8mb4',
        );

        $this->pdo = new PDO(
            $dsn,
            $_ENV['DATA_BASE_USER'] ?? 'root',
            $_ENV['DATA_BASE_PASS'] ?? '',
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
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
        return $this->pdo;
    }
}
