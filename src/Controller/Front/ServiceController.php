<?php

namespace App\Controller\Front;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/service/{page<\d+>?1}', name: 'app_service_show')]
    public function index(ServiceRepository $serviceRepository, Request $request, int $page = 1): Response
    {
        $itemsPerPage = 3;
        $services = $serviceRepository->findAllService();
        $totalPages = ceil(count($services) / $itemsPerPage);
        $page = max(1, min($page, $totalPages));
     
        return $this->render('front/service/index.html.twig', [
            'services' => $services,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
