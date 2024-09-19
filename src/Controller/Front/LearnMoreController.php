<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LearnMoreController extends AbstractController
{
    #[Route('/en-savoir-plus', name: 'app_learn_more')]
    public function index(): Response
    {
        return $this->render('front/learnMore/index.html.twig');
    }
}
