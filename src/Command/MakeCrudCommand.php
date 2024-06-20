<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;

#[AsCommand(
    name: 'app:make:crud',
    description: 'Add a short description for your command',
)]
class MakeCrudCommand extends Command
{
    private string $projectDir;
    private string $entity_name;
    private string $variable_name;
    

    private PropertyListExtractorInterface $propertyInfo;
    /**
     * @var string[]|null
     */
    private ?array $properties;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->projectDir = $kernel->getProjectDir();

        $reflectionExtractor = new ReflectionExtractor();
        $doctrineExtractor = new DoctrineExtractor($entityManager);

        $this->propertyInfo = new PropertyInfoExtractor(
            // List extractors
            [
                $reflectionExtractor,
                $doctrineExtractor
            ],
            // Type extractors
            [
                $doctrineExtractor,
                $reflectionExtractor
            ]
        );

    }

    protected function configure(): void
    {
        $this
            ->addArgument('entity_name', InputArgument::REQUIRED, 'Nom de l\'entity')
            ->addArgument('template_type', InputArgument::REQUIRED, 'Type de template');
       
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->entity_name = $input->getArgument('entity_name');
        $this->variable_name = strtolower($this->entity_name);

        $class = 'App\\Entity\\'.$this->entity_name;

        $this->properties = $this->propertyInfo->getProperties($class);

        //suppression de l'id
        $this->properties = array_diff($this->properties,['id','ulid']);

        //creation du controller
        $this->makeController();
        $this->makeRepository();
        $this->makeFormType();

        //creation des templates
        $templatesDirectory = $this->projectDir.'/templates/'. $this->variable_name;
        if (file_exists($templatesDirectory)) {
            // Supprimer le répertoire même s'il contient des fichiers
            array_map('unlink', glob($templatesDirectory . '/*.*'));
            rmdir($templatesDirectory);
        }
        mkdir($templatesDirectory);
        $this->makeTemplate('_delete_button', $input);
        $this->makeTemplate('_delete_modal', $input);
        $this->makeTemplate('_form', $input);
        $this->makeTemplate('index', $input);
        $this->makeTemplate('show',$input);
        $this->makeTemplate('edit', $input);

        $io->success('Création du crud : '. $this->entity_name);

        return Command::SUCCESS;
    }

    public function interact(InputInterface $input, OutputInterface $output)
    {
        
        $questions = [];
        if (!$input->getArgument('entity_name')) {
            $question = new Question('Renseigner le nom de l\'entité pour générer le controller, le formtype et les templates : ');
            $question->setValidator(function ($entity_name) use ($output) {
                if (empty($entity_name)) {
                    $output->writeln('<error>Le nom de l\'entité ne peut être vide.</error>');
                    exit(1);
                }
                return $entity_name;

            });
            $questions['entity_name'] = $question;
            
        }

        if (!$input->getArgument('template_type')) {
            $question = new Question('Quel est le type de templates souhaité, table ou card ? ');
            $question->setValidator(function ($template_type) use ($output) {
                if (empty($template_type) ) {
                    $output->writeln('<error>Le choix de template ne peut être vide.</error>');
                    exit(1);
                } else if ($template_type !== 'table' && $template_type !== 'card'){
                    $output->writeln('<error>Choix non valide : ' . $template_type . '</error>');
                    exit(1);
                }
                return $template_type;
                

            });
            $questions['template_type'] = $question;
        }
              
        foreach ($questions as $name => $question) {
            $reponse = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $reponse);
             
        }

    }

    private function makeRepository(){
        $temp = file_get_contents($this->projectDir.'/crud/repository.php.crud');

        $temp = str_replace(['{{ ENTITY_NAME }}',],[$this->entity_name],$temp);

        $formDirectory = $this->projectDir.'/src/Repository';

        if (!file_exists($formDirectory)) {
            mkdir($formDirectory.'/');
        }

        file_put_contents($this->projectDir.'/src/Repository/'.$this->entity_name.'Repository.php',$temp);

    }

    private function makeFormType(){
        $temp = file_get_contents($this->projectDir.'/crud/formType.php.crud');

        $builder = '';
        foreach ($this->properties as $property){
            $builder .= '->add(\'' . $property . '\')
            ';
        }
        $temp = str_replace(['{{ ENTITY_NAME }}','{{ VARIABLE_NAME }}','{{ BUILDER }}'],[$this->entity_name, $this->variable_name, $builder],$temp);

        $formDirectory = $this->projectDir.'/src/Form';
        if (!file_exists($formDirectory)) {
            mkdir($formDirectory.'/');
        }

        file_put_contents($this->projectDir.'/src/Form/'.$this->entity_name.'Type.php',$temp);
    }

    private function makeController(){
        $temp = file_get_contents($this->projectDir.'/crud/controller.php.crud');
        $temp = str_replace(['{{ ENTITY_NAME }}','{{ VARIABLE_NAME }}'],[$this->entity_name, $this->variable_name],$temp);

        $controllerDirectory = $this->projectDir.'/src/Controller';
        if (!file_exists($controllerDirectory)) {
            mkdir($controllerDirectory.'/');
        }

        file_put_contents($this->projectDir.'/src/Controller/Admin/'.$this->entity_name.'Controller.php',$temp);
    }

    private function makeTemplate($name, $input){

        $index_list_th = '';
        $index_list_td = '';
        $index_list_tr_td = '';
        

        $index_list_th = implode("\n", array_map(function($property) {
            return '                <th class="text-center">'.$property.'</th>';
        }, $this->properties));
        $index_list_td= implode("\n", array_map(function($property) {
            return '                <td class="text-center">{{ '.$this->variable_name.'.'.$property.' }}</td>';
        }, $this->properties));
        $index_list_tr_td = implode("\n", array_map(function($property) {
            return '            <tr>
                <th>'.$property.'</th>
                <td>{{ '.$this->variable_name.'.'.$property.' }}</td>
            </tr>';
        }, $this->properties));

        
        if($input->getArgument('template_type') == 'table'){ 
            $temp = file_get_contents($this->projectDir.'/crud/view/table/'.$name.'.html.crud');
        } 
        if($input->getArgument('template_type') == 'card') {
            $temp = file_get_contents($this->projectDir.'/crud/view/card/'.$name.'.html.crud');
        }
    
        $temp = str_replace(
            ['{{ ENTITY_NAME }}',
                '{{ VARIABLE_NAME }}',
                '{{ INDEX_LIST_TH }}',
                '{{ INDEX_LIST_TD }}',
                '{{ INDEX_LIST_TR_TD }}'
            ],
            [$this->entity_name,
                $this->variable_name,
                $index_list_th,
                $index_list_td,
                $index_list_tr_td
            ],$temp);

        $templatesDirectory = $this->projectDir.'/templates/';
        if (!file_exists($templatesDirectory)) {
            mkdir($templatesDirectory.'');
        }    

        file_put_contents($this->projectDir.'/templates/admin/'.$this->variable_name.'/'.$name.'.html.twig',$temp);
    }

}
