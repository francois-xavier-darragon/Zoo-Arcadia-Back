<?php

namespace App\Command;

use App\Service\DatabaseService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:drop-database',
    description: 'Add a short description for your command',
)]
class DropDatabaseCommand extends Command
{
    protected static $defaultName = 'app:drop-database';
    private $databaseService;
    private $databaseUrl;

    public function __construct(DatabaseService $databaseService, string $databaseUrl)
    {
        parent::__construct();
        $this->databaseService = $databaseService;
        $this->databaseUrl = $databaseUrl;
    }

    protected function configure()
    {
        $this
            ->setDescription('Drops the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dbopts = parse_url($this->databaseUrl);
        $dbName = ltrim($dbopts['path'], '/');

        try {
            $sql = sprintf('DROP DATABASE IF EXISTS `%s`;', $dbName);
            $this->databaseService->query($sql);
            $io->success(sprintf('Database `%s` dropped successfully.', $dbName));
        } catch (\PDOException $e) {
            $io->error('Connection error: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
