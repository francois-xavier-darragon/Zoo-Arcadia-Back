<?php

namespace App\Controller\Admin;

use App\Entity\Notice;
use App\Form\NoticeType;
use App\Repository\NoticeRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/notices')]
class NoticeController extends AbstractController
{
    #[Route('/{page<\d+>?1}', name: 'app_admin_notice_index', methods: ['GET'])]
    public function index(NoticeRepository $noticeRepository, PaginationService $paginationService, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper, int $page = 1,): Response
    {
      
        $notices = $noticeRepository->findAllnotice(['deleted_At'=> null]);
        $itemsPerPage = 10;
        $paginationData = $paginationService->paginate($notices, $page, $itemsPerPage);
        $csrfTokens = [];

        foreach ($notices as $notice) {
            $csrfTokens[$notice->getId()] = $csrfTokenManager->getToken('delete-notice' . $notice->getId())->getValue();
        }

        return $this->render('admin/notice/index.html.twig', [
            'notices' => $paginationData['items'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalItems'],
            'itemsPerPage' => $itemsPerPage,
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,
            'uploaderHelper' => $uploaderHelper,
            'arrayStatut' => Notice::STATUT
        ]);
    }

    #[Route('/new', name: 'app_admin_notice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NoticeRepository $noticeRepository): Response
    {
        $user =  $this->getUser();
        $roles = $user->getRoles();

        $notice = new Notice();
        $form = $this->createForm(NoticeType::class, $notice, ['roles' => $roles]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noticeRepository->saveNotice($notice, true);

            return $this->redirectToRoute('app_admin_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/notice/edit.html.twig', [
            'notice' => $notice,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_notice_show', methods: ['GET','POST'])]
    public function read(Request $request, Notice $notice,NoticeRepository $noticeRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-notice' . $notice->getId())->getValue();

        $form = $this->createForm(NoticeType::class, $notice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $statut = $form->get('status')->getData();
            $notice->setVisible($statut);

            $noticeRepository->saveNotice($notice, true);

            return $this->redirectToRoute('app_admin_notice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/notice/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'notice' => $notice,
            'form' => $form,
            'delete_btn' => true,
            'arrayStatut' => Notice::STATUT
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
