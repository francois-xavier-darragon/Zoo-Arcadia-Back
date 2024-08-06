<?php

namespace App\Controller\Admin;

use App\Entity\Enclosure;
use App\Entity\Habitat;
use App\Form\HabitatType;
use App\Repository\EnclosureRepository;
use App\Repository\HabitatRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/habitats')]
class HabitatController extends AbstractController
{
    #[Route('/', name: 'app_admin_habitat_index', methods: ['GET'])]
    public function index(HabitatRepository $habitatRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $habitats = $habitatRepository->findAllhabitat();
        $csrfTokens = [];

        foreach ($habitats as $habitat) {
            $csrfTokens[$habitat->getId()] = $csrfTokenManager->getToken('delete-habitat' . $habitat->getId())->getValue();
        }

        return $this->render('admin/habitat/index.html.twig', [
            'habitats' => $habitats,
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,
        ]);
    }

    #[Route('/new', name: 'app_admin_habitat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HabitatRepository $habitatRepository, EnclosureRepository $enclosureRepository): Response
    {
        $habitat = new Habitat();
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                     
            foreach ($habitat->getEnclosures() as $enclosure) {
                $enclosure->setHabitat($habitat);
            }
            
            $habitatRepository->saveHabitat($habitat, true);

            return $this->redirectToRoute('app_admin_habitat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/habitat/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_habitat_show', methods: ['GET'])]
    public function read(Habitat $habitat, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-habitat' . $habitat->getId())->getValue();

        return $this->render('admin/habitat/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'habitat' => $habitat,
            'delete_btn' => true,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_habitat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitat $habitat, HabitatRepository $habitatRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-habitat' . $habitat->getId())->getValue();

        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        $existingImages = [];

        $reflectionClass = new \ReflectionClass($habitat);

        $entityName = strtolower($reflectionClass->getShortName()).'s';

        $images = $habitat->getImages();
        foreach ($images as $image) {

            $path =  '/uploads/images/'. $entityName .'/'. $image->getName();
            $existingImages[] = [
                'id' => $image->getId(),
                'path' => $path
            ];
        }

        $enclosures = [];
        $enclosureName = null;
        foreach($habitat->getEnclosures() as $enclosure) {
            $enclosureName = new \ReflectionClass($enclosure);
            $enclosures [$enclosure->getId()] = $enclosure->getImages();
        }

        $enclosureImage = [];

        foreach($enclosures as $images) {
            foreach($images as $image) {
                $path =  '/uploads/images/'. $enclosureName .'/'. $image->getName();
                $enclosureImage[] = [
                    'id' => $image->getId(),
                    'path' => $path
                ];
            }
        }

      
        if ($form->isSubmitted() && $form->isValid()) {
        
            $habitatRepository->saveHabitat($habitat, true);

            return $this->redirectToRoute('app_admin_habitat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/habitat/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'habitat' => $habitat,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
            'existingImages' => json_encode($existingImages),
            'enclosureImage' => json_encode($enclosureImage),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_habitat_delete', methods: ['POST'])]
    public function delete(Request $request, Habitat $habitat, HabitatRepository $habitatRepository): Response
    {
        if($habitat->getDeletedAt()){
            return $this->redirectToRoute('app_admin_habitat_index');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-habitat'.$habitat->getId(), $submittedToken)) {
            $habitatRepository->removeHabitat($habitat, true);

            $this->addFlash('success', 'Le utilisateur "'.$habitat->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_habitat_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet habitat, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_habitat_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{habitat}/remove-habitat-image/', name: 'app_admin_habitat_remove_image', methods: ['POST'])]
    public function removeAnimalImage(Request $request, Habitat $habitat, HabitatRepository $habitatRepository, ImageRepository $imageRepository): JsonResponse
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

        $habitat->removeImage($image);
        $habitatRepository->saveHabitat($habitat, true);

        $imageRepository->removeImage($image, true);

        return new JsonResponse(['status' => 'success'], 200);
    }

}
