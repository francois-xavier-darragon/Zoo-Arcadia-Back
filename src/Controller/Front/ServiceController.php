<?php

namespace App\Controller\Front;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service_show')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAllService();
     
        return $this->render('front/service/index.html.twig', [
            'services' => $services,
        ]);
    }
}
