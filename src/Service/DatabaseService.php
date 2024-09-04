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
    private $dbname;

    public function __construct(string $databaseUrl)
    {
        $dbopts = parse_url($databaseUrl);
        
        $this->dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s',
            $dbopts['host'],
            $dbopts['port'],
            ltrim($dbopts['path'], '/')
        );
        
        $this->user = $dbopts['user'];
        $this->password = $dbopts['pass'];

        $this->connect();
    }

    public function connect($withDatabase = true)
    {
        try {
            $dsn = $this->dsn;
            if ($withDatabase) {
                $dsn .= ';dbname=' . $this->dbname;
            }
            $this->connection = new PDO($dsn, $this->user, $this->password);
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
