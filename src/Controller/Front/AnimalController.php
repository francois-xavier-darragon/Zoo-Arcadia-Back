<?php

namespace App\Controller\Front;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'app_animal_views_count', methods: ['POST'])]
    public function index(Animal $animal, AnimalRepository $animalRepository): JsonResponse
    {
        $newViewCount = $animalRepository->incrementViewCount($animal);

        return $this->json(['views' => $newViewCount]);
    }
}
