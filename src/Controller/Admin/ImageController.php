<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/images')]
class ImageController extends AbstractController
{
    #[Route('/', name: 'app_admin_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-token');
        
        return $this->render('admin/image/index.html.twig', [
            'images' => $imageRepository->findAllImage(),
            'csrfToken'     => $csrfToken->getValue(),
            'delete_btn'    => true
        ]);
    }

    #[Route('/new', name: 'app_admin_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageRepository $repository): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveImage($image, true);

            return $this->redirectToRoute('app_admin_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_image_show', methods: ['GET'])]
    public function read(Image $image): Response
    {
        return $this->render('admin/image/show.html.twig', [
            'image' => $image,
            'delete_btn' => true
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image, ImageRepository $repository): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveImage($image, true);

            return $this->redirectToRoute('app_admin_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image, ImageRepository $repository): Response
    {
        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-image', $submittedToken)) {
            $repository->removeImage($image, true);

            $this->addFlash('success', 'Le utilisateur "'.$image->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_image_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet image, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
