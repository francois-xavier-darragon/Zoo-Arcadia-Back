<?php

namespace App\Controller\Front;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        
        $form = $this->createForm(ContactType::class);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/contact/index.html.twig', [
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }
}
