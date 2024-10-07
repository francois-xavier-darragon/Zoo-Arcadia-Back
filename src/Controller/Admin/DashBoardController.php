<?php

namespace App\Controller\Admin;

use App\Document\AnimalViews;
use App\Entity\Notice;
use App\Repository\AnimalRepository;
use App\Repository\NoticeRepository;
use App\Service\PaginationService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/dashboard')]
class DashBoardController extends AbstractController
{
    
    #[Route('/{page<\d+>?1}', name: 'app_admin_dashboard', methods: ['GET'])]
    public function index(NoticeRepository $noticeRepository, AnimalRepository $animalRepository, PaginationService $paginationService, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper, DocumentManager $dm, int $page = 1,): Response
    {
        $topAnimal = $animalRepository->findMostViewedAnimal();
        $nbviews = null;

        if ($topAnimal != null ) {
            $animalId = $topAnimal->getId();
            $nbviewsDoc = $dm->getRepository(AnimalViews::class)->findOneBy(['animalId' => $animalId]);
            $nbviews = $nbviewsDoc ? $nbviewsDoc->getViews() : 0;
        }

        $notices = $noticeRepository->findAllnotice(['deleted_At'=> null]);
        $itemsPerPage = 10;
        $paginationData = $paginationService->paginate($notices, $page, $itemsPerPage);
        $csrfTokens = [];

        foreach ($notices as $notice) {
            $csrfTokens[$notice->getId()] = $csrfTokenManager->getToken('delete-notice' . $notice->getId())->getValue();
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'notices' => $paginationData['items'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalItems'],
            'itemsPerPage' => $itemsPerPage,
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,
            'uploaderHelper' => $uploaderHelper,
            'arrayStatut' => Notice::STATUT,
            'topAnimal' => $topAnimal,
            'nbviews' => $nbviews
        ]);
    }
}
