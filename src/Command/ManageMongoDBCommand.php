<?php

namespace App\Command;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ManageMongoDBCommand extends Command
{
    protected static $defaultName = 'app:manage:mongodb';
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        parent::__construct();
        $this->documentManager = $documentManager;
    }

    protected function configure()
    {
        $this->setDescription('Importe une base de données de MongoDB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $dbName = $this->chooseDatabase($io);
        $this->importDatabase($dbName, $io);

        return Command::SUCCESS;
    }

    private function chooseDatabase(SymfonyStyle $io): string
    {
        // Get list of databases
        $databases = $this->documentManager->getClient()->listDatabases();
        $dbNames = [];
        foreach ($databases as $database) {
            $dbNames[] = $database['name'];
        }

        if (empty($dbNames)) {
            $io->error("Aucune base de données existante trouvée.");
            exit(1);
        }

        // Ask the user to choose a database
        return $io->choice('Sélectionnez une base de données à importer', $dbNames);
    }

    private function importDatabase(string $dbName, SymfonyStyle $io): void
    {
        // Load environment variables
        $mongodbUrl = $_ENV['MONGODB_URL'] ?? $_ENV['ORMONGO_URL'] ?? $_ENV['MONGODB_URL'];
        $dbName = $_ENV['MONGODB_DB'];

        // Extract host and port from MongoDB URL
        $urlParts = parse_url($mongodbUrl);
        $host = $urlParts['host'];
        $port = $urlParts['port'] ?? '27017';

        $dumpDir = './nosql/';
        $dumpFolders = glob($dumpDir . '*', GLOB_ONLYDIR);
        if (empty($dumpFolders)) {
            $io->error("Aucun dossier de dump trouvé dans $dumpDir");
            return;
        }

        $selectedFolder = $io->choice('Sélectionnez le dossier de dump à importer', array_map('basename', $dumpFolders));
        $dumpPath = $dumpDir . $selectedFolder;

        $db = $this->documentManager->getClient()->selectDatabase($dbName);

        // Check if the database already contains data
        $collections = iterator_to_array($db->listCollections());
        if (!empty($collections)) {
            if (!$io->confirm("La base de données '$dbName' contient déjà des données. Voulez-vous les écraser ?", false)) {
                $io->info("Importation annulée.");
                return;
            }
            // Delete existing collections
            foreach ($collections as $collection) {
                $db->dropCollection($collection->getName());
            }
        }

        $metadataFiles = glob($dumpPath . '/*.metadata.json');
        foreach ($metadataFiles as $metadataFile) {
            $collectionName = basename($metadataFile, '.metadata.json');
            $bsonFile = $dumpPath . '/' . $collectionName . '.bson';

            if (!file_exists($bsonFile)) {
                $io->warning("Fichier BSON manquant pour $collectionName, ignoré.");
                continue;
            }

            $io->text("Importation de la collection $collectionName...");

            // Create the collection and its indexes
            $metadata = json_decode(file_get_contents($metadataFile), true);
            $collection = $db->createCollection($collectionName);
            if (isset($metadata['indexes'])) {
                foreach ($metadata['indexes'] as $index) {
                    if ($index['name'] !== '_id_') {  // Skip the default _id index
                        $collection->createIndex($index['key'], ['name' => $index['name']]);
                    }
                }
            }

            // Import BSON data
            $command = sprintf(
                'mongorestore --host %s --port %s --db %s --collection %s %s',
                $host,
                $port,
                $dbName,
                $collectionName,
                $bsonFile
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                $io->error("Erreur lors de l'importation de $collectionName: " . implode("\n", $output));
            } else {
                $io->success("Collection $collectionName importée avec succès.");
            }
        }

        $io->success("Importation du dump terminée pour la base de données '$dbName'.");
    }

}
