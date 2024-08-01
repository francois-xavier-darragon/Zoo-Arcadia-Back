<?php

namespace App\Controller\Front;

use App\Entity\Animal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal/{id}', name: 'app_animal_show', methods: ['GET'])]
    public function index(Animal $animal): Response
    {
        return $this->render('front/animal/read.html.twig', [
            'animal' => $animal,
        ]);
    }
}
