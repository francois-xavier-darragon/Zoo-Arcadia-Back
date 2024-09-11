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
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ManageDatabaseCommand extends Command
{
    protected static $defaultName = 'app:manage:database';
    private $entityManager;
    private $databaseService;
    private $databaseUrl;
    private ?string $selectedFileName = null;
    private $sqlDir;

    public function __construct(EntityManagerInterface $entityManager, DatabaseService $databaseService, string $databaseUrl,  string $projectDir)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->databaseService = $databaseService;
        $this->databaseUrl = $databaseUrl;
        $this->sqlDir = $projectDir . '/sql/';
    }

    // configuration
    protected function configure()
    {
        $this
            ->setDescription('Creates, drops, updates the database, or imports SQL data.')
            ->addArgument('action', InputArgument::OPTIONAL, 'The action to perform: create, drop, update, or import')
            ->addArgument('dbname', InputArgument::OPTIONAL, 'The name of the database (for create action)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');

        if (!$action) {
            $question = new ChoiceQuestion(
                'Veuillez sélectionner l\'action à effectuer',
                ['drop', 'create', 'update', 'import'],
                0
            );
            $question->setErrorMessage('L\'action %s n\'est pas valide.');
    
            $action = $io->askQuestion($question);
            $input->setArgument('action', $action);
        }
    
        if ($action === 'create') {
            $dbName = $input->getArgument('dbname');
            if (!$dbName) {
                $dbName = $io->ask('Veuillez entrer le nom de la base de données à créer :');
                $input->setArgument('dbname', $dbName);
            }
        } else {
            $dbopts = parse_url($this->databaseUrl);
            $dbName = ltrim($dbopts['path'], '/');
        }

        if ($action === 'import') {
    
            $files = glob($this->sqlDir . '*.sql');
    
            if (empty($files)) {
                $io->error("Aucun fichier SQL trouvé dans le répertoire $this->sqlDir");
                return Command::FAILURE;
            }
        
            $io->section('Fichiers SQL disponibles :');
            $choices = [];
            foreach ($files as $file) {
                $choices[] = basename($file);
            }
        
            $question = new ChoiceQuestion(
                'Choisissez le fichier à importer',
                $choices
            );
        
            $question->setErrorMessage('Le fichier %s n\'est pas valide.');
            
            $this->selectedFileName = $io->askQuestion($question);

            $io->writeln("Fichier sélectionné : " . $this->selectedFileName);
        }

        $dbopts = parse_url($this->databaseUrl);
        $dbName = ltrim($dbopts['path'], '/');

        try {
            switch ($action) {
                case 'create':
                    $this->createDatabase($dbName, $io);
                    break;
                case 'drop':
                    $this->dropDatabase($dbName, $io);
                    break;
                case 'update':
                    $this->updateDatabase($dbName, $io);
                    break;
                case 'import':
                    $filename = $this->selectedFileName;
                    if (!$filename) {
                        throw new InvalidArgumentException('The SQL filename is required for the import action.');
                    }
                    $filePath = $this->sqlDir . $filename;
                    if (!file_exists($filePath)) {
                        throw new InvalidArgumentException("The SQL file '$filename' does not exist in the SQL directory.");
                    }
                    $this->importDatabase($dbName, $filePath, $io);
                    break;
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

                if($table == "doctrine_migration_version") {
                    continue;
                }

                $isCombinedTable = false;

                // check if there are join tables
                foreach ($tables as $otherTable) {
                    if ($table !== $otherTable && strpos($table, $otherTable) !== false) {
                        $isCombinedTable = true;
                       
                    }
                }

                // Table to exclude list
                if( $isCombinedTable){
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
    private function processTables(array $metadata, string $method, array $checkVerifcation, SymfonyStyle $io): void
    {

        $entityColumns = [];
        $tablesToSkip = ['file'];

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

            $entityNameAndPropertyNumber = [];
            foreach($entityColumns as $key => $table) {
                $entityNameAndPropertyNumber[$key] = count($table);
            }

            $entityNameAndPropertyNumberInBdd = [];
            foreach($checkVerifcation as $key => $table) {
                $entityNameAndPropertyNumberInBdd[$key] = count($table);
            }

            if ($method === 'createTable') {
                $this->createTable($meta);
            } elseif ($method === 'updateTable'){
                $this->updateTable($meta);
            }else {
                $io->success('Database schema not need updated.');
            }

        }

        if ($method === 'createTable' && !$this->existsTableOrColumn('doctrine_migration_version')) {
            $this->createDoctrineMigrationVersionTable();
        }

        if ($method === 'createTable' && !$this->existsTableOrColumn('messenger_messages')) {
            $this->createMessengerMessages();
        }

    }


    // create table
    private function createTable($meta): void
    {
        $tableName = $meta->getTableName();

        // Check if the table already exists before attempting to create it
        if ($this->existsTableOrColumn($tableName)) {
            return;
        } else {

             // Create the columns for the table
        $columns = [];
        foreach ($meta->getFieldNames() as $fieldName) {
            $fieldMapping = $meta->getFieldMapping($fieldName);
            $columns[] = $this->getColumnDefinition($fieldMapping);
        }

        // Ensure the first column is a primary key (id)
        $columns[0] = 'id INT NOT NULL AUTO_INCREMENT PRIMARY KEY';

        // Build table creation SQL query

        $sql = sprintf('CREATE TABLE %s (%s)', $tableName, implode(', ', $columns));
        $this->databaseService->query($sql);

        }
    }

    // Create obligatory doctrine migration version table  
    private function createDoctrineMigrationVersionTable(): void
    {
        $this->databaseService->query('
            CREATE TABLE doctrine_migration_version (
                version VARCHAR(191) NOT NULL,
                executed_at DATETIME NULL,
                executed_time INT NULL,
                PRIMARY KEY(version)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');
    }

    // Create obligatory doctrine messenger messages table 
    private function createMessengerMessages(): void
    {
        $this->databaseService->query('
            CREATE TABLE messenger_messages (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                body LONGTEXT NOT NULL,
                headers LONGTEXT DEFAULT NULL,
                queue_name VARCHAR(190) NOT NULL,
                created_at DATETIME NOT NULL,
                available_at DATETIME NOT NULL,
                delivered_at DATETIME NULL
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');
    }

    // Update table for association mappings
    private function updateTable($meta): void
    {
        foreach ($meta->getAssociationMappings() as $associationMapping) {
            if ($associationMapping['type'] === ClassMetadata::MANY_TO_ONE || $associationMapping['type'] === ClassMetadata::ONE_TO_ONE) {
                $columnName = $associationMapping['fieldName'] . '_id';
                
                $targetEntityTableName = $this->entityManager->getClassMetadata($associationMapping['targetEntity'])->getTableName();
                
                $constraintName = sprintf('FK_%s_%s', $meta->getTableName(), $associationMapping['fieldName']);
            
                // Determine if the column should allow NULL
                $nullable = $this->isAssociationNullable($associationMapping);

                // Check if the column exists
                if (!$this->existsTableOrColumn($meta->getTableName(), $columnName)) {
                    try {
                        // Add the column and foreign key constraint
                        $sql = sprintf('ALTER TABLE %s ADD %s INT %s, ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s(id)',
                            $meta->getTableName(), $columnName,
                            $nullable ? 'NULL' : 'NOT NULL',
                            $constraintName, $columnName, $targetEntityTableName
                        );
        
                        $this->databaseService->query($sql);
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                } else {
                    // Column already exists, check if foreign key constraint exists
                    if (!$this->foreignKeyExists($meta->getTableName(), $constraintName)) {
                        try {
                            // Add only foreign key constraint
                            $sql = sprintf('ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s(id)',
                                $meta->getTableName(), $constraintName, $columnName, $targetEntityTableName
                            );
                            $this->databaseService->query($sql);
                        } catch (PDOException $e) {
                            echo 'Error: ' . $e->getMessage();
                        }
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

    // Checks if a specific foreign key constraint already exists
    private function foreignKeyExists(string $tableName, string $constraintName): bool
    {
        $sql = "SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
                WHERE CONSTRAINT_SCHEMA = DATABASE() 
                AND TABLE_NAME = '$tableName' 
                AND CONSTRAINT_NAME = '$constraintName' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'";
        
        $stmt = $this->databaseService->getConnection()->query($sql);
        $result = $stmt->fetchColumn();
        return $result > 0;
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
            'datetime', 'datetime_immutable' => sprintf('%s DATETIME %s COMMENT \'(DC2Type:%s)\'', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL', $fieldMapping->type),
            'date' => sprintf('%s DATE %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'time' => sprintf('%s TIME %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'decimal' => sprintf('%s DECIMAL(10, 2) %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'float' => sprintf('%s FLOAT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'array', 'simple_array', 'object' => sprintf('%s TEXT %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            'json' => sprintf('%s JSON %s', $fieldMapping->columnName, $fieldMapping->nullable ? 'NULL' : 'NOT NULL'),
            default => throw new \InvalidArgumentException(sprintf('Unknown doctrine type "%s"', $fieldMapping->type)),
        };
    }

    //method to import sql data
    private function importDatabase(string $dbName, string $filePath, SymfonyStyle $io): void
    {
        $this->databaseService->selectDatabase($dbName);
        $pdo = $this->databaseService->getConnection();

        // Check if there is existing data
        $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
       
        $hasData = false;

        if (!empty($tables)) {
            foreach ($tables as $table) {
                $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                if ($count > 0) {
                    $hasData = true;
                    break;
                }
            }
        }
      
        if ($hasData) {
            $question = new ConfirmationQuestion(
                'La base de données contient déjà des données. Voulez-vous les écraser ? (y/n) ',
                false
            );

            if (!$io->askQuestion($question)) {
                $io->warning('Importation annulée par l\'utilisateur.');
                return;
            }
        }

        $io->info("Importation des données depuis " . basename($filePath) . " vers la base de données $dbName.");

        $sql = file_get_contents($filePath);
        $statements = explode(';', $sql);

        try {
            // Disable foreign key constraints
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    
            // Empty all existing tables if user confirmed
            if ($hasData) {
                foreach ($tables as $table) {
                    $pdo->exec("TRUNCATE TABLE `$table`");
                }
                $io->info("Toutes les tables ont été vidées.");
            }
    
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
    
            $io->success("Les données ont été importées avec succès.");
        } catch (\PDOException $e) {
            $io->error("Erreur lors de l'importation des données : " . $e->getMessage());
        } finally {
            // Re-enable foreign key constraints
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
