<?php

namespace App\Service;

use PDO;
use PDOException;

class DatabaseService
{
    private $connection;
    private $dsn;
    private $user;
    private $password;

    public function __construct(string $databaseUrl, string $environment)
    {
        $dbopts = parse_url($databaseUrl);
        
        if ($environment === 'prod') {
            // Configuration for PostgreSQL (Heroku)
            $this->dsn = sprintf(
                'pgsql:host=%s;port=%d;dbname=%s',
                $dbopts['host'],
                $dbopts['port'],
                ltrim($dbopts['path'], '/')
            );
        } else {
            // Configuration for MySQL (local development)
            $this->dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s',
                $dbopts['host'],
                $dbopts['port'] ?? 3306,
                ltrim($dbopts['path'], '/')
            );
        }
        
        $this->user = $dbopts['user'];
        $this->password = $dbopts['pass'];

        $this->connect();
    }

    private function connect()
    {
        try {
            $this->connection = new PDO($this->dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new \RuntimeException('Connection error: ' . $e->getMessage());
        }
    }

    public function selectDatabase(string $dbName)
    {
        
        try {
            $this->connection->exec("USE `$dbName`");
        } catch (PDOException $e) {
            throw new \RuntimeException('Database selection error: ' . $e->getMessage());
        }

        return $dbName;
    }
    
    public function query(string $sql, array $parameters = [])
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);

        return $statement;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
