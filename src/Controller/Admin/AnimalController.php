<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\Breed;
use App\Entity\VeterinaryReport;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use App\Repository\BreedRepository;
use App\Repository\ImageRepository;
use App\Repository\VeterinaryReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/animals')]
class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_admin_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $animals = $animalRepository->findAllanimal(['deleted_At'=> null]);
        $csrfTokens = [];

        foreach ($animals as $animal) {
            $csrfTokens[$animal->getId()] = $csrfTokenManager->getToken('delete-animal' . $animal->getId())->getValue();
        }

        return $this->render('admin/animal/index.html.twig', [
            'animals' => $animalRepository->findAllAnimal(),
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,
        ]);
    }

    #[Route('/new', name: 'app_admin_animal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnimalRepository $animalRepository, BreedRepository $breedRepository, VeterinaryReportRepository $veterinaryReportRepository): Response
    {
        $breeds = $breedRepository->findAllBreed(['deleted_At'=> null]);
        $countBreeds = count($breeds) === 0;
       
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal, [
            'countBreeds' => $countBreeds
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newBreed = $form->get('addbreed')->getData();

            if($newBreed != null){
                $breed = new Breed;
                $breed->setName($newBreed);
                $breedRepository->saveBreed($breed, true);
                $animal->setBreed($breed);
            }

            $newVeterinaryReports = $form->get('veterinaryReports')->getData();
            $veterinaryReport = new veterinaryReport();
            $veterinaryReport->setDetail($newVeterinaryReports);

            $veterinaryReportRepository->saveVeterinaryReport($veterinaryReport, true);
            $animal->addVeterinaryReport($veterinaryReport);

            $animalRepository->saveAnimal($animal, true);

            return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
            'mode' => 'Ajouter',
            'countBreeds' => $countBreeds
        ]);
    }

    #[Route('/{id}', name: 'app_admin_animal_show', methods: ['GET'])]
    public function read(Animal $animal, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-animal' . $animal->getId())->getValue();

        return $this->render('admin/animal/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'animal' => $animal,
            'delete_btn' => true,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, AnimalRepository $animalRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-animal' . $animal->getId())->getValue();

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animalRepository->saveAnimal($animal, true);

            return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/animal/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'animal' => $animal,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, AnimalRepository $animalRepository): Response
    {
        if($animal->getDeletedAt()){
            return $this->redirectToRoute('app_admin_animal_index');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-animal'.$animal->getId(), $submittedToken)) {
            $animalRepository->removeAnimal($animal, true);

            $this->addFlash('success', 'Le utilisateur "'.$animal->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_animal_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet animal, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{user}/remove-animal-image', name: 'app_admin_user_remove_avatar', methods: ['POST'])]
    // public function removeAnimalImage(Animal $animal, AnimalRepository $userRepository, ImageRepository $imageRepository): JsonResponse
    // {
    //     $image = $animal->getImages();
    //     if ($image) {
    //         $image = $imageRepository->findOneById($animal->getAvatar());
    //         $animal->addImage(null);
    //         $userRepository->saveAnimal($animal, true);
    //         $imageRepository->removeImage($image, true);
    //         return new JsonResponse(['status' => 'success'], 200);
    //     }
    //     return new JsonResponse(['status' => 'error', 'message' => 'No avatar to remove'], 400);
    // }
}
