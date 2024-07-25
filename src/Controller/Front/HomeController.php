<?php

namespace App\Controller\Front;

use App\Repository\AnimalRepository;
use App\Repository\HabitatRepository;
use App\Repository\NoticeRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/accueil', name: 'app_home')]
    public function index(HabitatRepository $habitatRepository, AnimalRepository $animalRepository, NoticeRepository $noticeRepository, ServiceRepository $serviceRepository): Response
    {
        $habitats = $habitatRepository->findAllHabitat();
        $animals = $animalRepository->findAllAnimal();
        $notice = $noticeRepository->findAllNotice();
        $services = $serviceRepository->findAllService();

        $randomAnimal = $animals[array_rand($animals)];

        return $this->render('front/home/index.html.twig', [
            'habitats'       => $habitats,
            'animals'        => $animals,
            'notices'        => $notice,
            'randomAnimal'   => $randomAnimal,
            'services'       => $services
        ]);
    }
}
