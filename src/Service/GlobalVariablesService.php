<?php

namespace App\Service;

use App\Repository\HabitatRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class GlobalVariablesService
{
    public function __construct(
        private Environment $twig,
        private HabitatRepository $habitatRepository)
    {}

    public function onKernelController(ControllerEvent $event): void
    {

        if (!$event->isMainRequest()) {
            return;
        }

        $habitats = $this->habitatRepository->findAll();
        $this->twig->addGlobal('habitats', $habitats);
    }
}