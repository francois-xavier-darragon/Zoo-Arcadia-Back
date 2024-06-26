<?php

namespace App\Command;

use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\FieldMapping;
use InvalidArgumentException;
use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ManageDatabaseCommand extends Command
{
    protected static $defaultName = 'app:manage:database';
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

    // configuration
    protected function configure()
    {
        $this
            ->setDescription('Creates, drops, or updates the database.')
            ->addArgument('action', InputArgument::REQUIRED, 'The action to perform: create, drop, or update');
    }

    // command execution
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');

        if (!in_array($action, ['create', 'drop', 'update'])) {
            throw new InvalidArgumentException('Invalid action. Use "create", "drop", or "update".');
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

            return Command::SUCCESS;
        } catch (\PDOException $e) {
            $io->error('Connection error: ' . $e->getMessage());
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
          
        }

        return Command::FAILURE;
    }

    // create database
    private function createDatabase(string $dbName, SymfonyStyle $io): void
    {
       
        $sql = sprintf('CREATE DATABASE IF NOT EXISTS `%s`;', $dbName);
        $this->databaseService->query($sql);
        $io->success(sprintf('Database `%s` created successfully.', $dbName));
    }

    // drop database
    private function dropDatabase(string $dbName, SymfonyStyle $io): void
    {
        $sql = sprintf('DROP DATABASE IF EXISTS `%s`;', $dbName);
        $this->databaseService->query($sql);
        $io->success(sprintf('Database `%s` dropped successfully.', $dbName));
    }

    // updating the database
    private function updateDatabase(string $dbName, SymfonyStyle $io): void
    {
        $database = $this->databaseService->selectDatabase($dbName);
        
        $checkVerifcation = $this->checkVerification($database);
        
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        
        // Step 1: Create all tables without relations
        $this->processTables($metadata, 'createTable', $checkVerifcation ,$io);

        // Step 2: Update all tables to add relations
        $this->processTables($metadata, 'updateTable', $checkVerifcation, $io);

        $io->success('Database schema updated successfully.');
    }

    // method which checks existing tables and columns in the database
    private function checkVerification($database)
    {
        $tables = [];
        $tableColumns = [];

        try {
           
            $sqlTables = "SHOW TABLES FROM $database";
            $stmtTables = $this->databaseService->getConnection()->query($sqlTables);
            $tables = $stmtTables->fetchAll(PDO::FETCH_COLUMN);
            
            foreach ($tables as $table) {
                $isCombinedTable = false;

                // check if there are join tables
                foreach ($tables as $otherTable) {
                    if ($table !== $otherTable && strpos($table, $otherTable) !== false) {
                        $isCombinedTable = true;
                       
                    }
                }

                // Table to exclude list
                if($table == "doctrine_migration_versions" || $isCombinedTable){
                    continue;
                }

                $tableColumns[$table] = [];
                
                // Retrieving the columns of each table
                $sqlColumns = "SHOW COLUMNS FROM $table";
                $stmtColumns = $this->databaseService->getConnection()->query($sqlColumns);
                $columns = $stmtColumns->fetchAll(PDO::FETCH_COLUMN);
                
                // Add the columns to the list of columns for this table
                $tableColumns[$table] = $columns;
            }
            
        } catch (PDOException $e) {
            // Handling connection or PDO request errors
            echo "Erreur PDO : " . $e->getMessage();
        }

        return $tableColumns;
    }

    // creation or update process
    private function processTables(array $metadata, string $method, array $checkVerifcation): void
    {
        $entityColumns = [];
        $tablesToSkip = ['file', 'messenger_messages'];

        // Step 1: Collect column names for each table, skipping specified tables
        foreach ($metadata as $meta) {
            $tableName = $meta->getTableName();

            if (in_array($tableName, $tablesToSkip)) {
                continue;
            }
            
            // Store column names for each table
            $entityColumns[$tableName] = array_values($meta->getColumnNames());
        }

        // Step 2: Process each table for creating or updating based on the method and verification
        foreach ($metadata as $meta) {
            $tableName = $meta->getTableName();

            if (in_array($tableName, $tablesToSkip)) {
                continue;
            }


            $countNumberByTableEntityColumns = [];
            foreach($entityColumns as $key => $table) {
                $countNumberByTableEntityColumns[$key] = count($table);
            }
            $countNumberByTableCheckVerifcation = [];
            foreach($checkVerifcation as $key => $table) {
                $countNumberByTableCheckVerifcation[$key] = count($table);
            }

         //TODO à modifier car les nouvelles colonnes ne sont pas ajouté
            // Check if the number of tables to process matches the verification array
            // if(count($entityColumns) != count($checkVerifcation)) {
                
                if ($method === 'createTable') {
                    $this->createTable($meta);
                } elseif ($method === 'updateTable'){
                    $this->updateTable($meta);
                }
            // }
        }

    }

    // create table
    private function createTable($meta): void
    {
        $tableName = $meta->getTableName();
        
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

    // updates table and add relation ManyToOne, OneToOne or ManyToMany
    private function updateTable($meta): void
    {
        foreach ($meta->getAssociationMappings() as $associationMapping) {
           
            if ($associationMapping['type'] === ClassMetadata::MANY_TO_ONE || $associationMapping['type'] === ClassMetadata::ONE_TO_ONE) {
                $columnName = $associationMapping['fieldName'];
                
                $targetEntityTableName = $this->entityManager->getClassMetadata($associationMapping['targetEntity'])->getTableName();
                
                $constraintName = sprintf('FK_%s_%s', $meta->getTableName(), $columnName);
              
                // Determine if the column should allow NULL
                $nullable = $this->isAssociationNullable($associationMapping);

                // Check if the column exists
                if (!$this->existsTableOrColumn($meta->getTableName(), $columnName)) {
                    
                    try {
                        // Add the column and foreign key constraint
                        $sql = sprintf('ALTER TABLE %s ADD %s_id INT %s, ADD CONSTRAINT %s FOREIGN KEY (%s_id) REFERENCES %s(id)',
                            $meta->getTableName(), $columnName,
                            $nullable ? 'NULL' : 'NOT NULL',
                            $constraintName, $columnName, $targetEntityTableName
                        );
        
                        $this->databaseService->query($sql);
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                }

            } elseif($associationMapping['type'] === ClassMetadata::MANY_TO_MANY) {
                $this->updateManyToManyColumn($meta, $associationMapping);
            }
        }
    }

    // add relation ManytoMany
    private function updateManyToManyColumn($meta, $associationMapping): void
    {
        $createdTables = [];

        $sourceTableName = $meta->getTableName();
        $targetEntityMeta = $this->entityManager->getClassMetadata($associationMapping['targetEntity']);
        $targetTableName = $targetEntityMeta->getTableName();

        $sortedTables = [$sourceTableName, $targetTableName];
        sort($sortedTables);
        $joinTableName = implode('_', $sortedTables);

        if (!in_array($joinTableName, $createdTables)) {
            $createdTables[] = $joinTableName;
            $sourceColumnName = strtolower($sourceTableName) . '_id';
            $targetColumnName = strtolower($targetTableName) . '_id';

            $nullable = $this->isAssociationNullable($associationMapping);

            if (!$this->existsTableOrColumn($joinTableName)) {
                $this->createJoinTable($joinTableName, $sourceColumnName, $targetColumnName, );
            } else {
                $this->checkAndAddColumns($joinTableName, [
                    ['columnName' => $sourceColumnName, 'referencedTableName' => $sourceTableName, 'nullable' => $nullable],
                    ['columnName' => $targetColumnName, 'referencedTableName' => $targetTableName, 'nullable' => $nullable]
                ]);
            }
        }
    }

    // check if table or column exists
    private function exists(string $type, string $tableName, ?string $columnName = null): bool
    {
        $sql = match ($type) {
            'table' => sprintf("SHOW TABLES LIKE '%s'", $tableName),
            'column' => sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $tableName, $columnName),
            default => throw new InvalidArgumentException('Invalid type specified. Use "table" or "column".'),
        };

        $stmt = $this->databaseService->getConnection()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return !empty($result);
    }

    // call the function exists
    private function existsTableOrColumn(string $name, ?string $columnName = null): bool
    {
        if ($columnName !== null) {
           return $this->exists('column', $name, $columnName);
        } else {
           return $this->exists('table', $name);
        }
    }

    // create a join table
    private function createJoinTable($joinTableName, $sourceColumnName, $targetColumnName)
    {
        try {
            $sql = sprintf(
                'CREATE TABLE %s (%s INT NOT NULL, %s INT NOT NULL, PRIMARY KEY(%s, %s))',
                $joinTableName,
                $sourceColumnName, $targetColumnName,
                $sourceColumnName, $targetColumnName
            );
            $this->databaseService->query($sql);
        } catch (PDOException $e) {
            echo 'Error creating table: ' . $e->getMessage();
        }
    }

    // check and add a column
    private function checkAndAddColumns($joinTableName, $columns)
    {
        foreach ($columns as $column) {
            $columnName = $column['columnName'];
            $referencedTableName = $column['referencedTableName'];
            $nullable = $column['nullable'];

            if (!$this->existsTableOrColumn($joinTableName, $columnName)) {
                $this->addColumnToJoinTable($joinTableName, $columnName, $referencedTableName, $nullable);
            }
        }
    }

    // add join column
    private function addColumnToJoinTable(string $joinTableName, string $columnName, string $referencedTableName, bool $nullable = false): void
    {
        try {
            $sql = sprintf(
                'ALTER TABLE %s ADD %s INT, ADD CONSTRAINT FK_%s_%s FOREIGN KEY (%s) REFERENCES %s(id)',
                $joinTableName, $columnName,
                $nullable ? 'NULL' : 'NOT NULL',
                $joinTableName, $columnName, $columnName, $referencedTableName
            );
            $this->databaseService->query($sql);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Helper function to determine if association is nullable
    private function isAssociationNullable($associationMapping): bool
    {
        // Check if the association mapping indicates the column should be nullable
        if (is_array($associationMapping)) {
            // Check if the association mapping indicates the column should be nullable
            if (isset($associationMapping['joinColumns'][0]['nullable'])) {
                return $associationMapping['joinColumns'][0]['nullable'];
            }
        } elseif (is_object($associationMapping)) {
            // Assuming $associationMapping is an instance of OneToOneOwningSideMapping
            // or similar class. Adjust based on actual class used.
            if (isset($associationMapping->joinColumns[0]->nullable)) {
                return $associationMapping->joinColumns[0]->nullable;
            }
        }
        
        // By default, assume it's nullable if not explicitly required
        return true;
    }

    // retrieves the column type and its definition
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
