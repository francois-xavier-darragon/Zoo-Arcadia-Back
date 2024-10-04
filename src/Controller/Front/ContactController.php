<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use App\Service\MailService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, ParameterBagInterface $params): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
         
            $mail = new MailService($params);

            $variables = [
                'email' => $data['email'],
                'message' => $data['message']
            ];

            $result = $mail->send($_ENV['MAILER_SENDER'], $data['email'], $variables);

            if ($result['success']) {
                $this->addFlash('success', 'Votre message a été envoyé avec succès.');
            } else {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }
        }

        return $this->render('front/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
