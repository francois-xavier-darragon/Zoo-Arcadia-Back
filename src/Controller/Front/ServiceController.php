<?php

namespace App\Controller\Front;

use App\Repository\ServiceRepository;
use App\Service\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{page<\d+>?1}', name: 'app_service_show')]
    public function index(ServiceRepository $serviceRepository, PaginationService $paginationService,  int $page = 1): Response
    {
        $itemsPerPage = 3;
        $services = $serviceRepository->findAllService(['deleted_At'=> null]);
        $paginationData = $paginationService->paginate($services, $page, $itemsPerPage);
     
        return $this->render('front/service/index.html.twig', [
            'services' => $paginationData['items'],
            'currentPage' => $paginationData['currentPage'],
            'totalPages' => $paginationData['totalPages'],
            'totalItems' => $paginationData['totalItems'],
        ]);
    }
}
