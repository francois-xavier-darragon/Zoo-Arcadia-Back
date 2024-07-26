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
    public function __construct(
        private HabitatRepository $habitatRepository,
        private AnimalRepository $animalRepository, 
        private NoticeRepository $noticeRepository, 
        private ServiceRepository $serviceRepository,
    ){}

    #[Route('/accueil', name: 'app_home')]
    public function index(): Response
    {
        $habitats = $this->habitatRepository->findAllHabitat();
        $animals = $this->animalRepository->findAllAnimal();
        $notice = $this->noticeRepository->findAllNotice();
        $services = $this->serviceRepository->findAllService();

        $randomAnimal = $animals[array_rand($animals)];

        return $this->render('front/home/index.html.twig', [
            'habitats'       => $habitats,
            'animals'        => $animals,
            'notices'        => $notice,
            'randomAnimal'   => $randomAnimal,
            'services'       => $services,
        ]);
    }
}
