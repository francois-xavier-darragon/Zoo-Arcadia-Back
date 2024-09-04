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

        $roles = $user->getRoles();
        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice, ['roles' => $roles]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $notice->setUser($user);
        
            $noticeRepository->saveNotice($notice, true);

            return $this->redirectToRoute('app_admin_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/notice/new.html.twig', [
            'notice' => $notice,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }
}
