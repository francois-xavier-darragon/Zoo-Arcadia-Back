<?php

namespace App\Controller\Admin;

use App\Entity\Enclosure;
use App\Entity\Habitat;
use App\Form\EnclosureType;
use App\Repository\EnclosureRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/habitat')]
class EnclosureController extends AbstractController
{

    #[Route('/{id}/enclosure/new', name: 'app_admin_enclosure_new', methods: ['GET', 'POST'])]
    public function new(Habitat $habitat, Request $request, EnclosureRepository $enclosureRepository): Response
    {
        $enclosure = new Enclosure();

        $form = $this->createForm(EnclosureType::class, $enclosure);

        $form->handleRequest($request);
        $enclosure->setHabitat($habitat); 
        
        if ($form->isSubmitted() && $form->isValid()) {
                   
            $enclosureRepository->saveEnclosure($enclosure, true);

            return $this->redirectToRoute('app_admin_habitat_edit', ['id'=> $habitat->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/enclosure/edit.html.twig', [
            'enclosure' => $enclosure,
            'habitat' => $habitat,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{enclosure}', name: 'app_admin_enclosure_show', methods: ['GET'])]
    public function read(Enclosure $enclosure, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-habitat' . $enclosure->getId())->getValue();

        return $this->render('admin/habitat/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'enclosure' => $enclosure,
            'delete_btn' => true,
        ]);
    }

    #[Route('/enclosure/edit/{enclosure}/', name: 'app_admin_enclosure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enclosure $enclosure, EnclosureRepository $enclosureRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-enclosure' . $enclosure->getId())->getValue();

        $form = $this->createForm(EnclosureType::class, $enclosure);
        $form->handleRequest($request);

        $existingImages = [];

        $reflectionClass = new \ReflectionClass($enclosure);

        $entitiName = strtolower($reflectionClass->getShortName()).'s';

        $images = $enclosure->getImages();
        foreach ($images as $image) {

            $path =  '/uploads/images/'. $entitiName .'/'. $image->getName();
            $existingImages[] = [
                'id' => $image->getId(),
                'path' => $path
            ];
        }

        if ($form->isSubmitted() && $form->isValid()) {
        
            $enclosureRepository->saveEnclosure($enclosure, true);

            return $this->redirectToRoute('app_admin_habitat_edit', ['id'=> $enclosure->getHabitat()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/enclosure/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'enclosure' => $enclosure,
            'habitat' => $enclosure->getHabitat(),
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
            'existingImages' => json_encode($existingImages)
        ]);
    }

    #[Route('/enclosure/delete/{enclosure}/', name: 'app_admin_enclosure_delete', methods: ['POST'])]
    public function delete(Request $request, Enclosure $enclosure, EnclosureRepository $enclosureRepository): Response
    {
        // if($enclosure->getDeletedAt()){
        //     return $this->redirectToRoute('app_admin_enclosure_index');
        // }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-enclosure'.$enclosure->getId(), $submittedToken)) {
            $enclosureRepository->removeEnclosure($enclosure, true);

            $this->addFlash('success', 'Le utilisateur "'.$enclosure->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_habitat_edit',['id'=> $enclosure->getHabitat()->getId()]);
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet enclosure, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_habitat_edit',['id'=> $enclosure->getHabitat()->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/enclosure/{enclosure}/remove-enclosure-image/', name: 'app_admin_enclosure_remove_image', methods: ['POST'])]
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
        $enclosureRepository->saveEnclosure($enclosure, true);

        $imageRepository->removeImage($image, true);

        return new JsonResponse(['status' => 'success'], 200);
    }
}
