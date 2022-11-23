<?php

namespace config;

use PDO;
use PDOException;

class Database
{
    public function getConnection(): PDO
    {
        try {
            $connection = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        } catch(PDOException $e) {
            echo "Database could not be connected: " . $e->getMessage();
            die;
        }

        return $connection;
    }
}
