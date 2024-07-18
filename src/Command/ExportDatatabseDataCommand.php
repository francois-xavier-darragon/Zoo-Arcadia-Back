<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\DatabaseService;

class ExportDatatabseDataCommand extends Command
{
    protected static $defaultName = 'app:export-database-data';
    private $dbService;

    public function __construct(DatabaseService $dbService)
    {
        $this->dbService = $dbService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Export database data to a SQL file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dbName = $io->ask('Entrez le nom de la base de données à exporter :', 'ma_base');
        $this->dbService->selectDatabase($dbName);

        $fileName = $io->ask('Entrez le nom du fichier SQL (sans extension) :', 'export');

        $exportDir = getcwd() . '/sql/';
        if (!file_exists($exportDir)) {
            mkdir($exportDir, 0755, true);
        }
        $filePath = $exportDir . $fileName . '.sql';

        // Check if file already exists
        $counter = 1;
        $originalFilePath = $filePath;
        while (file_exists($filePath)) {
            $filePath = preg_replace('/(\(\d+\))?\.sql$/', '', $originalFilePath);
            $filePath .= "($counter).sql";
            $counter++;
        }

        if ($filePath !== $originalFilePath) {
            $io->writeln("Le fichier $originalFilePath existe déjà. Un nouveau fichier sera créé : $filePath");
        }

        // Get list of tables
        $tables = $this->dbService->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        // Open file in write mode
        $file = fopen($filePath, 'w');

        foreach ($tables as $table) {
            // Export data from each table
            $rows = $this->dbService->query("SELECT * FROM `$table`")->fetchAll(\PDO::FETCH_ASSOC);
            
            if (!empty($rows)) {
                fwrite($file, "INSERT INTO `$table` VALUES\n");
                
                $rowCount = count($rows);
                foreach ($rows as $i => $row) {
                    $values = array_map(function ($value) {
                        if ($value === null) {
                            return 'NULL';
                        }
                        return $this->dbService->getConnection()->quote($value);
                    }, $row);
                    
                    fwrite($file, "(" . implode(", ", $values) . ")");
                    
                    if ($i < $rowCount - 1) {
                        fwrite($file, ",\n");
                    } else {
                        fwrite($file, ";\n\n");
                    }
                }
            }
        }

        fclose($file);

        $io->success("Les données ont été exportées avec succès dans le fichier $filePath");

        return Command::SUCCESS;
    }
}