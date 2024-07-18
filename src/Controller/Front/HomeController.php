<?php

namespace App\Controller\Front;

use App\Repository\AnimalRepository;
use App\Repository\HabitatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'app_home')]
    public function index(HabitatRepository $habitatRepository, AnimalRepository $animalRepository): Response
    {
        $habitats = $habitatRepository->findAllHabitat();
        $animals = $animalRepository->findAllAnimal();

        return $this->render('front/home/index.html.twig', [
            'habitats' => $habitats,
            'animals'=> $animals
        ]);
    }
}
