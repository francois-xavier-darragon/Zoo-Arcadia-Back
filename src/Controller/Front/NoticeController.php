<?php

namespace App\Controller\Front;

use App\Entity\Notice;
use App\Form\NoticeType;
use App\Repository\NoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NoticeController extends AbstractController
{
    #[Route('/notice', name: 'app_notice_new')]
    public function new(Request $request, NoticeRepository $noticeRepository): Response
    {
        $user =  $this->getUser();
        
        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($user != null) {
                $notice->setUser($user);
            }
              
            $noticeRepository->saveNotice($notice, true);

            $this->addFlash('success', "Merci d'avoir laissé un avis !");

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/notice/new.html.twig', [
            'notice' => $notice,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }
}
