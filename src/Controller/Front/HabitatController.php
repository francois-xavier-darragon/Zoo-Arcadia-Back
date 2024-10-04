<?php

namespace App\Controller\Front;

use App\Entity\Habitat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HabitatController extends AbstractController
{
    #[Route('/habitat/{id}', name: 'app_habitat_show', methods: ['GET'])]
    public function index(Habitat $habitat): Response
    {
        return $this->render('front/habitat/read.html.twig', [
            'habitat' => $habitat,
        ]);
    }
}
