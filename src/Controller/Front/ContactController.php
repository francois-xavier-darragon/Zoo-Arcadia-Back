<?php

namespace App\Controller\Front;

use App\Form\ContactType;
use SendGrid;
use SendGrid\Mail\Mail;
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

            $contactFormData = $form->getData();
            
            $email = new Mail();
            $email->setFrom($contactFormData['email']); 
            $email->setSubject('Sending with Twilio SendGrid is Fun');
            $email->addTo('fxd15130@egmail.com');
            $email->addContent('text/plain', 'Envoyé par : ' .$contactFormData['lastname'] ." ". $contactFormData['firstname']."\n".
                    'Email : '.$contactFormData['email']."\n".
                    'Message : '.$contactFormData['message']
            );

            $sendgrid = new SendGrid($this->getParameter('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);

                if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
                    $this->addFlash('success', 'Votre message a été envoyé avec succès.');
                } else {
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.');
                }
                return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                return new Response('Caught exception: '. $e->getMessage(), 500);
            }
        
           
        }

        return $this->render('front/contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
