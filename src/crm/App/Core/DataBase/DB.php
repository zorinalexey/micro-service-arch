<?php

namespace App\Core\DataBase;

use PDO;
use PDOException;

final class DB
{
    private PDO|false $connection;
    
    public function __construct (string $host, int $port, string $dbname, string $user, string $password)
    {
        try {
            $this->connection = new PDO("pgsql:host=$host;dbname=$dbname;port=$port", $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print_r($e);
        }
    }
    
    public function getConnection (): PDO
    {
        return $this->connection;
    }
}