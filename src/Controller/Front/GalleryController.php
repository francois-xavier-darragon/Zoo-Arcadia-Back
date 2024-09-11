<?php

namespace App\Controller\Front;

use App\Repository\AnimalRepository;
use App\Repository\EnclosureRepository;
use App\Repository\HabitatRepository;
use App\Repository\ImageRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalleryController extends AbstractController
{
    #[Route('/gallery/{type}', name: 'app_gallery', defaults: ['type' => 'habitats'])]
    public function index(HabitatRepository $habitatRepository, EnclosureRepository $enclosureRepository, AnimalRepository $animalRepository, string $type = 'habitats'): Response {
       
        $repository = match ($type) {
            'habitats' => $habitatRepository,
            'enclosures' => $enclosureRepository,
            'animals' => $animalRepository,
            default => throw new \InvalidArgumentException("Type de galerie invalide: $type"),
        };

        $gallery = $this->getGallery($repository);

        return $this->render("front/gallery/index.html.twig", [
            'gallery' => $gallery,
            'currentType' => $type
        ]);
    }
    
    private function getGallery(ServiceEntityRepository $repository): array
    {
        $items = $repository->findAll();
        return array_map(fn($item) => [
            'name' => $item->getName(),
            'images' => $item->getImages()
        ], $items);
    }
}
