<?php

namespace App\Controller\Admin;

use App\Entity\Notice;
use App\Form\NoticeType;
use App\Repository\NoticeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/notices')]
class NoticeController extends AbstractController
{
    #[Route('/', name: 'app_admin_notice_index', methods: ['GET'])]
    public function index(NoticeRepository $noticeRepository, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper): Response
    {
        $notices = $noticeRepository->findAllnotice(['deleted_At'=> null]);
        $csrfTokens = [];

        foreach ($notices as $notice) {
            $csrfTokens[$notice->getId()] = $csrfTokenManager->getToken('delete-notice' . $notice->getId())->getValue();
        }

        return $this->render('admin/notice/index.html.twig', [
            'notices' => $noticeRepository->findAllNotice(),
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,
            'uploaderHelper' => $uploaderHelper
        ]);
    }

    #[Route('/new', name: 'app_admin_notice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NoticeRepository $noticeRepository, UploaderHelper $uploaderHelper): Response
    {
        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noticeRepository->saveNotice($notice, true);

            return $this->redirectToRoute('app_admin_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/notice/edit.html.twig', [
            'notice' => $notice,
            'form' => $form,
            'mode' => 'Ajouter',
            // 'uploaderHelper' => $uploaderHelper,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_notice_show', methods: ['GET'])]
    public function read(Notice $notice, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-notice' . $notice->getId())->getValue();

        return $this->render('admin/notice/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'notice' => $notice,
            'delete_btn' => true,
            // 'uploaderHelper' => $uploaderHelper,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_notice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notice $notice, NoticeRepository $noticeRepository, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-notice' . $notice->getId())->getValue();

        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noticeRepository->saveNotice($notice, true);

            return $this->redirectToRoute('app_admin_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/notice/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'notice' => $notice,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
            'uploaderHelper' => $uploaderHelper,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_notice_delete', methods: ['POST'])]
    public function delete(Request $request, Notice $notice, NoticeRepository $noticeRepository): Response
    {
        if($notice->getDeletedAt()){
            return $this->redirectToRoute('app_admin_notice_index');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-notice'.$notice->getId(), $submittedToken)) {
            $noticeRepository->removeNotice($notice, true);

            $this->addFlash('success', 'Le utilisateur "'.$notice->getNickname().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_notice_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet notice, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_notice_index', [], Response::HTTP_SEE_OTHER);
    }
}
