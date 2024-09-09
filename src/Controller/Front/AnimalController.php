<?php

namespace App\Controller\Front;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'app_animal_show', methods: ['GET'])]
    public function index(Animal $animal, AnimalRepository $animalRepository): Response
    {
        $animalRepository->incrementViewCount($animal);

        return $this->render('front/animal/read.html.twig', [
            'animal' => $animal,
        ]);
    }
}
