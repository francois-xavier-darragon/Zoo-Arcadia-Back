<?php

namespace App\Service;

use App\Entity\USER;
use App\Repository\EnclosureRepository;
use App\Repository\HabitatRepository;
use App\Repository\NoticeRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class GlobalVariablesService
{
    public function __construct(
        private Environment $twig,
        private HabitatRepository $habitatRepository,
        private NoticeRepository $noticeRepository,
        private UserRepository $userRepository,
        private EnclosureRepository $enclosureRepository,
        private ServiceRepository $serviceRepository
    )
    {}

    public function onKernelController(ControllerEvent $event): void
    {

        if (!$event->isMainRequest()) {
            return;
        }

        $habitats = $this->habitatRepository->findAll();
        $this->twig->addGlobal('habitats', $habitats);

        $enclosures = $this->enclosureRepository->findAll();
        $this->twig->addGlobal('enclosures', $enclosures);

        $services = $this->serviceRepository->findAll();
        $this->twig->addGlobal('services', $services);

        $pendingNoticesCount = $this->noticeRepository->countPendingNotices();
        $this->twig->addGlobal('pending_notices_count', $pendingNoticesCount);
    }
}
