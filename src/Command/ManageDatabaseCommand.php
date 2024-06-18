<?php

namespace App\Command;

use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\FieldMapping;
use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ManageDatabaseCommand extends Command
{
    protected static $defaultName = 'app:manage-database';
    private $entityManager;
    private $databaseService;
    private $databaseUrl;

    public function __construct(EntityManagerInterface $entityManager, DatabaseService $databaseService, string $databaseUrl)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->databaseService = $databaseService;
        $this->databaseUrl = $databaseUrl;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates, drops, or updates the database.')
            ->addArgument('action', InputArgument::REQUIRED, 'The action to perform: create, drop, or update');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');

        if (!in_array($action, ['create', 'drop', 'update'])) {
            $io->error('Invalid action. Use "create", "drop", or "update".');
            return Command::FAILURE;
        }

        $dbopts = parse_url($this->databaseUrl);
        $dbName = ltrim($dbopts['path'], '/');

        try {
            if ($action === 'create') {
                $this->createDatabase($dbName, $io);
            } elseif ($action === 'drop') {
                $this->dropDatabase($dbName, $io);
            } elseif ($action === 'update') {
                $this->updateDatabase($dbName, $io);
            }
        } catch (\PDOException $e) {
            $io->error('Connection error: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function createDatabase(string $dbName, SymfonyStyle $io): void
    {
        $sql = sprintf('CREATE DATABASE IF NOT EXISTS `%s`;', $dbName);
        $this->databaseService->query($sql);
        $io->success(sprintf('Database `%s` created successfully.', $dbName));
    }

    private function dropDatabase(string $dbName, SymfonyStyle $io): void
    {
        $sql = sprintf('DROP DATABASE IF EXISTS `%s`;', $dbName);
        $this->databaseService->query($sql);
        $io->success(sprintf('Database `%s` dropped successfully.', $dbName));
    }

    private function updateDatabase(string $dbName, SymfonyStyle $io): void
    {
        $this->databaseService->selectDatabase($dbName);

        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        
        // Step 1: Create all tables without relations
        $this->processTables($metadata, 'createTable');

        // Step 2: Update all tables to add relations
        $this->processTables($metadata, 'updateTable');

        $io->success('Database schema updated successfully.');
    }

    private function processTables(array $metadata, string $method): void
    {
        foreach ($metadata as $meta) {
            $tableName = $meta->getTableName();

            // Check if table exists
            $stmt = $this->databaseService->getConnection()->query(sprintf("SHOW TABLES LIKE '%s'", $tableName));
            $tableExists = $stmt->rowCount() > 0;

            // Create or update table based on the method passed
            if ($method === 'createTable' && !$tableExists) {
                $this->createTable($meta);
            } elseif ($method === 'updateTable' && $tableExists) {
                $this->updateTable($meta);
            }
        }
    }

    private function createTable($meta): void
    {
        $tableName = $meta->getTableName();
        
        // Create table
        $columns = [];
        foreach ($meta->getFieldNames() as $fieldName) {
            $fieldMapping = $meta->getFieldMapping($fieldName);
            $columns[] = $this->getColumnDefinition($fieldMapping);
        }

        // Ensure the id column is a primary key
        $columns[0] = 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY';
        
        $sql = sprintf('CREATE TABLE %s (%s)', $tableName, implode(', ', $columns));
        
        $this->databaseService->query($sql);

    }

    private function updateTable($meta): void
    {
        $this->updateManyToOneColumn($meta);
    }

    private function updateManyToOneColumn($meta): void
    {
        foreach ($meta->getAssociationMappings() as $associationMapping) {
           
            if ($associationMapping['type'] === ClassMetadata::MANY_TO_ONE) {
                $columnName = $associationMapping['fieldName'];
                
                $targetEntityTableName = $this->entityManager->getClassMetadata($associationMapping['targetEntity'])->getTableName();
                $constraintName = sprintf('FK_%s_%s', $meta->getTableName(), $columnName);
              
                if (!$this->columnExists($meta->getTableName(), $columnName . '_id')) {
                    try {
                        // Set column as NOT NULL by default for a ManyToOne relationship
                        $sql = sprintf('ALTER TABLE %s ADD %s_id INT NOT NULL, ADD CONSTRAINT FK_%s FOREIGN KEY (%s_id) REFERENCES %s(id)',
                            $meta->getTableName(), $columnName,
                            $constraintName, $columnName, $targetEntityTableName
                        );
                   
                        $this->databaseService->query($sql);
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                }
            }
        }
    }

    private function columnExists(string $tableName, string $columnName): bool
    {
        $sql = sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $tableName, $columnName);
        $stmt = $this->databaseService->getConnection()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($result);
    }

    private function getColumnDefinition(FieldMapping $fieldMapping): string
    {
        return match ($fieldMapping->type) {
            'integer' => sprintf('%s INT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'smallint' => sprintf('%s SMALLINT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'bigint' => sprintf('%s BIGINT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'string' => sprintf('%s VARCHAR(255) %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'text' => sprintf('%s TEXT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'boolean' => sprintf('%s TINYINT(1) %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'datetime', 'datetime_immutable' => sprintf('%s DATETIME %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'date' => sprintf('%s DATE %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'time' => sprintf('%s TIME %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'decimal' => sprintf('%s DECIMAL(10, 2) %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'float' => sprintf('%s FLOAT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'array', 'simple_array', 'object' => sprintf('%s TEXT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'json' => sprintf('%s JSON %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            default => throw new \InvalidArgumentException(sprintf('Unknown doctrine type "%s"', $fieldMapping->type)),
        };
    }

}
