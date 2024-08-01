<?php

namespace App\Controller\Front;

use App\Entity\Enclosure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EnclosureController extends AbstractController
{
    #[Route('/enclosure/{id}', name: 'app_enclosure_show', methods: ['GET'])]
    public function index(Enclosure $enclosure): Response
    {
        return $this->render('front/enclosure/read.html.twig', [
            'enclosure' => $enclosure,
        ]);
    }
}
