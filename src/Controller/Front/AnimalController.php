<?php

namespace App\Controller\Front;

use App\Document\AnimalViews;
use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'app_animal_views_count', methods: ['POST'])]
    public function index(int $id,  DocumentManager $dm): JsonResponse
    {
        // $newViewCount = $animalRepository->incrementViewCount($animal);

        // return $this->json(['views' => $newViewCount]);

        $animalViews = $dm->getRepository(AnimalViews::class)->findOneBy(['animalId' => $id]);


        if (!$animalViews) {
            $animalViews = new AnimalViews();
            $animalViews->setAnimalId($id);
        }

        $animalViews->incrementViews();

       
        $dm->persist($animalViews);
        $dm->flush();

        return new JsonResponse(['views' => $animalViews->getViews()]);
    }
}
