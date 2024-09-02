<?php

namespace App\Controller\Admin;

use App\Entity\Enclosure;
use App\Form\EnclosureType;
use App\Repository\EnclosureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/habitat/{id}')]
class EnclosureController extends AbstractController
{

    #[Route('/enclosure/new', name: 'app_admin_enclosure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EnclosureRepository $enclosureRepository): Response
    {
        
        $enclosure = new Enclosure();

        $form = $this->createForm(EnclosureType::class, $enclosure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                     
            $enclosureRepository->saveEnclosure($enclosure, true);

            return $this->redirectToRoute('app_admin_enclosure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/enclosure/edit.html.twig', [
            'enclosure' => $enclosure,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/edit/{enclosure}/', name: 'app_admin_enclosure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enclosure $enclosure, EnclosureRepository $enclosureRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-enclosure' . $enclosure->getId())->getValue();

        $form = $this->createForm(EnclosureType::class, $enclosure);
        $form->handleRequest($request);

        $existingImages = [];

        $reflectionClass = new \ReflectionClass($enclosure);

        $entitiName = strtolower($reflectionClass->getShortName()).'s';

        // $images = $enclosure->getImages();
        // foreach ($images as $image) {

        //     $path =  '/uploads/images/'. $entitiName .'/'. $image->getName();
        //     $existingImages[] = [
        //         'id' => $image->getId(),
        //         'path' => $path
        //     ];
        // }

        if ($form->isSubmitted() && $form->isValid()) {
        
            $enclosureRepository->saveEnclosure($enclosure, true);

            return $this->redirectToRoute('app_admin_enclosure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/enclosure/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'enclosure' => $enclosure,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
            'existingImages' => json_encode($existingImages)
        ]);
    }

    #[Route('/delete/{enclosure}/', name: 'app_admin_enclosure_delete', methods: ['POST'])]
    public function delete(Request $request, Enclosure $enclosure, EnclosureRepository $enclosureRepository): Response
    {
        // if($enclosure->getDeletedAt()){
        //     return $this->redirectToRoute('app_admin_enclosure_index');
        // }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-enclosure'.$enclosure->getId(), $submittedToken)) {
            $enclosureRepository->removeEnclosure($enclosure, true);

            $this->addFlash('success', 'Le utilisateur "'.$enclosure->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_enclosure_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet enclosure, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_enclosure_index', [], Response::HTTP_SEE_OTHER);
    }
}
