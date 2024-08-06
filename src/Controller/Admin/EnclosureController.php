<?php

namespace App\Controller\Admin;

use App\Entity\Enclosure;
use App\Repository\EnclosureRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EnclosureController extends AbstractController
{
    #[Route('/{enclosure}/remove-enclosure-image/', name: 'app_admin_enclosure_remove_image', methods: ['POST'])]
    public function removeAnimalImage(Request $request, Enclosure $enclosure, EnclosureRepository $enclosureRepository, ImageRepository $imageRepository): JsonResponse
    {
     
        $data = json_decode($request->getContent(), true);
        $imageId = ($data['imgId']) ?? null;

        if ($imageId === null) {
            return new JsonResponse(['status' => 'error', 'message' => 'ID de l\'image manquant'], 400);
        }
     
        $image = $imageRepository->findOneById($imageId);

        if (!$image) {
            return new JsonResponse(['status' => 'error', 'message' => 'Image non trouvée'], 404);
        }

        $enclosure->removeImage($image);
        $enclosureRepository->saveenclosure($enclosure, true);

        $imageRepository->removeImage($image, true);

        return new JsonResponse(['status' => 'success'], 200);
    }
}
