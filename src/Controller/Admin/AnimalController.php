<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/animals')]
class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_admin_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('admin/animal/index.html.twig', [
            'animals' => $animalRepository->findAllAnimal(),
        ]);
    }

    #[Route('/new', name: 'app_admin_animal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnimalRepository $repository): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveAnimal($animal, true);

            return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_animal_show', methods: ['GET'])]
    public function read(Animal $animal): Response
    {
        return $this->render('admin/animal/show.html.twig', [
            'animal' => $animal,
            'delete_btn' => true
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, AnimalRepository $repository): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveAnimal($animal, true);

            return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
            'mode'=> 'Modifier',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, AnimalRepository $repository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->request->get('_token'))) {
        $repository->removeAnimal($animal);

        } else {

        }

        return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }
}
