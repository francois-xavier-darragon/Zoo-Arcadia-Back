<?php

namespace App\Command;

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

class MakeTraitCommand extends Command
{
    protected static $defaultName = 'make:trait';

    protected function configure()
    {
        $this
            ->setDescription('Creates a new trait')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the trait');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $helper = $this->getHelper('question');

        if (!$name) {
            $question = new Question('Please enter the name of the trait: ');
            $name = $helper->ask($input, $output, $question);
        }

        if (substr($name, -5) !== 'Trait') {
            $name .= 'Trait';
        }

        $traitDir = 'src/Entity/Trait';
        $traitPath = sprintf('src/Entity/Trait/%s.php', $name);

        if (!is_dir($traitDir)) {
            mkdir($traitDir, 0755, true);
        }

        if (file_exists($traitPath)) {
            $output->writeln(sprintf('<error>Trait %s already exists</error>', $name));
            return Command::FAILURE;
        }

        $traitTemplate = sprintf(
            "<?php\n\nnamespace App\Traits;\n\ntrait %s\n{\n    // Your code here\n}\n",
            $name
        );

        file_put_contents($traitPath, $traitTemplate);

        $output->writeln(sprintf('<info>Trait %s created successfully</info>', $name));

        return Command::SUCCESS;
    }
}
