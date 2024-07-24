<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ImageRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/services')]
class ServiceController extends AbstractController
{
    #[Route('/', name: 'app_admin_service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository, CsrfTokenManagerInterface $csrfTokenManager, ): Response
    {
        $services = $serviceRepository->findAllservice(['deleted_At'=> null]);
        $csrfTokens = [];

        foreach ($services as $service) {
            $csrfTokens[$service->getId()] = $csrfTokenManager->getToken('delete-service' . $service->getId())->getValue();
        }

        return $this->render('admin/service/index.html.twig', [
            'services' => $serviceRepository->findAllService(),
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,

        ]);
    }

    #[Route('/new', name: 'app_admin_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServiceRepository $serviceRepository, ): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $serviceRepository->saveService($service, true);

            return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/service/edit.html.twig', [
            'service' => $service,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_service_show', methods: ['GET'])]
    public function read(Service $service, CsrfTokenManagerInterface $csrfTokenManager, ): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-service' . $service->getId())->getValue();

        return $this->render('admin/service/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'service' => $service,
            'delete_btn' => true,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Service $service, ServiceRepository $serviceRepository, CsrfTokenManagerInterface $csrfTokenManager, ): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-service' . $service->getId())->getValue();

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        $existingImages = [];

        $reflectionClass = new \ReflectionClass($service);

        $entitiName = strtolower($reflectionClass->getShortName()).'s';

        $images = $service->getImages();
        foreach ($images as $image) {

            $path =  '/uploads/images/'. $entitiName .'/'. $image->getName();
            $existingImages[] = [
                'id' => $image->getId(),
                'path' => $path
            ];
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $serviceRepository->saveService($service, true);

            return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/service/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'service' => $service,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
            'existingImages' => json_encode($existingImages)
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_service_delete', methods: ['POST'])]
    public function delete(Request $request, Service $service, ServiceRepository $serviceRepository): Response
    {
        if($service->getDeletedAt()){
            return $this->redirectToRoute('app_admin_service_index');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-service'.$service->getId(), $submittedToken)) {
            $serviceRepository->removeService($service, true);

            $this->addFlash('success', 'Le utilisateur "'.$service->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_service_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet service, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_service_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{service}/remove-service-image/', name: 'app_admin_service_remove_image', methods: ['POST'])]
    public function removeAnimalImage(Request $request, Service $service, ServiceRepository $serviceRepository, ImageRepository $imageRepository): JsonResponse
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

        $service->removeImage($image);
        $serviceRepository->saveService($service, true);

        $imageRepository->removeImage($image, true);

        return new JsonResponse(['status' => 'success'], 200);
    }
}
