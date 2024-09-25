<?php

namespace App\Controller\Front;

use App\Repository\AnimalRepository;
use App\Repository\EnclosureRepository;
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
        private EnclosureRepository $enclosureRepository,
        private NoticeRepository $noticeRepository,
        private ServiceRepository $serviceRepository,
    ){}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $habitats = $this->habitatRepository->findAllHabitat();
        $enclosures = $this->enclosureRepository->findAllEnclosure();
        $notice = $this->noticeRepository->findAllNotice();
        $services = $this->serviceRepository->findAllService();

        $topAnimal = $this->animalRepository->findMostViewedAnimal();

        return $this->render('front/home/index.html.twig', [
            'habitats'       => $habitats,
            'enclosures'     => $enclosures,
            'notices'        => $notice,
            'topAnimal'      => $topAnimal,
            'services'       => $services,
        ]);
    }
}
