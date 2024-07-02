<?php

namespace App\Controller\Admin;

use App\Entity\Breed;
use App\Form\BreedType;
use App\Repository\BreedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/breeds')]
class BreedController extends AbstractController
{
    #[Route('/', name: 'app_admin_breed_index', methods: ['GET'])]
    public function index(BreedRepository $breedRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $breeds = $breedRepository->findAllbreed();
        $csrfTokens = [];

        foreach ($breeds as $breed) {
            $csrfTokens[$breed->getId()] = $csrfTokenManager->getToken('delete-breed' . $breed->getId())->getValue();
        }

        return $this->render('admin/breed/index.html.twig', [
            'breeds' => $breedRepository->findAllBreed(),
            'csrf_Tokens'    => $csrfTokens,
            'delete_btn'    => true
        ]);
    }

    #[Route('/new', name: 'app_admin_breed_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BreedRepository $breedRepository): Response
    {
        $breed = new Breed();
        $form = $this->createForm(BreedType::class, $breed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $breedRepository->saveBreed($breed, true);

            return $this->redirectToRoute('app_admin_breed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/breed/edit.html.twig', [
            'breed' => $breed,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_breed_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Breed $breed, BreedRepository $breedRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-breed' . $breed->getId())->getValue();

        $form = $this->createForm(BreedType::class, $breed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $breedRepository->saveBreed($breed, true);

            return $this->redirectToRoute('app_admin_breed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/breed/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'breed' => $breed,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_breed_delete', methods: ['POST'])]
    public function delete(Request $request, Breed $breed, BreedRepository $breedRepository): Response
    {
        if($breed->getDeletedAt()){
            return $this->redirectToRoute('app_admin_breed_index');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-breed'.$breed->getId(), $submittedToken)) {
            $breedRepository->removeBreed($breed, true);

            $this->addFlash('success', 'Le utilisateur "'.$breed->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_breed_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet breed, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_breed_index', [], Response::HTTP_SEE_OTHER);
    }
}
