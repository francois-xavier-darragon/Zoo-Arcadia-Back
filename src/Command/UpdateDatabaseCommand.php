<?php

namespace App\Command;

use App\Service\DatabaseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\FieldMapping;
use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateDatabaseCommand extends Command
{
    protected static $defaultName = 'app:update-database';
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
            ->setDescription('Updates the database schema based on entity metadata.');
    }
    //TODO verifier la supression en cascade des clefs étrangère
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dbopts = parse_url($this->databaseUrl);
        $dbName = ltrim($dbopts['path'], '/');

        try {
            $this->databaseService->selectDatabase($dbName);
            
            $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
           
            foreach ($metadata as $meta) {
                
                $tableName = $meta->table['name'];
    
                // Check if table exists
                $stmt = $this->databaseService->query(sprintf("SHOW TABLES LIKE '%s'", $tableName));
                $tableExists = $stmt->rowCount() > 0;
                
                // Create or update table
                if (!$tableExists) {
                    $this->createTable($meta);
                } else {
                    $this->updateTable($meta);
                }

                // Perform relationship updates only if the table already exists
              
            }

            $io->success('Database schema updated successfully.');
        } catch (PDOException $e) {
            $io->error('Connection error: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function createTable($meta)
    {
        $tableName = $meta->getTableName();
        
        // Create table
        $columns = [];
        foreach ($meta->getFieldNames() as $fieldName) {
            $fieldMapping = $meta->getFieldMapping($fieldName);
            $columns[] = $this->getColumnDefinition($fieldMapping);
        }

        // Ensure the primary key column is the first column
        $columns[0] = 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY';

        $sql = sprintf('CREATE TABLE %s (%s)', $tableName, implode(', ', $columns));
        $this->databaseService->query($sql);
    }

    private function updateTable($meta)
    {
        $tableName = $meta->getTableName();
        
        $currentColumns = [];
        foreach ($meta->getFieldNames() as $fieldName) {
            $fieldMapping = $meta->getFieldMapping($fieldName);
            $columnName = $fieldMapping['columnName'];
            $currentColumns[] = $columnName;

            // Check if column exists using DatabaseService
            $stmt = $this->databaseService->query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $tableName, $columnName));
            $columnExists = $stmt->rowCount() > 0;

            if (!$columnExists) {
                $sql = sprintf('ALTER TABLE %s ADD %s', $tableName, $this->getColumnDefinition($fieldMapping));
                $this->databaseService->query($sql);
            }
            
            // Get existing columns from the table
            $stmt = $this->databaseService->query(sprintf("SHOW COLUMNS FROM %s", $tableName));
            $existingColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Find columns to drop
            $columnsToDrop = array_diff($existingColumns, $currentColumns);
            foreach ($columnsToDrop as $columnToDrop) {
                $sql = sprintf('ALTER TABLE %s DROP COLUMN %s', $tableName, $columnToDrop);
                $this->databaseService->query($sql);
            }
        }
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
            default => throw new \InvalidArgumentException(sprintf('Unknown doctrine type "%s"', $fieldMapping->type)),
        };
    }
}
