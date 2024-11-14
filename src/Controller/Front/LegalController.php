<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/mentions-legales', name: 'legal_mentions')]
    public function index(Request $request): Response
    {
        $isAuthorized = $this->isGranted('ROLE_ADMIN') ||
                   $this->isGranted('ROLE_VETERINARY') ||
                   $this->isGranted('ROLE_WORKER') || str_contains($request->getPathInfo(), '/admin');

        return $this->render('front/legal/index.html.twig', [
            'layout' => $isAuthorized ? 'base.html.twig' : 'base_front.html.twig'
        ]);
    }
}
