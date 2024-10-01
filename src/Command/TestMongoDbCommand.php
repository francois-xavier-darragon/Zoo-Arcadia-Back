<?php

namespace App\Command;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:test-mongodb')]
class TestMongoDbCommand extends Command
{
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        parent::__construct();
        $this->dm = $dm;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $databases = $this->dm->getClient()->listDatabases();
            $output->writeln('Connexion à MongoDB réussie. Bases de données disponibles :');
            foreach ($databases as $database) {
                $output->writeln('- ' . $database->getName());
            }
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Erreur de connexion à MongoDB : ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}