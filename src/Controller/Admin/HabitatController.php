<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use App\Form\HabitatType;
use App\Repository\HabitatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/habitats')]
class HabitatController extends AbstractController
{
    #[Route('/', name: 'app_admin_habitat_index', methods: ['GET'])]
    public function index(HabitatRepository $habitatRepository): Response
    {
        return $this->render('habitat/index.html.twig', [
            'habitats' => $habitatRepository->findAllHabitat(),
        ]);
    }

    #[Route('/new', name: 'app_admin_habitat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HabitatRepository $repository): Response
    {
        $habitat = new Habitat();
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveHabitat($habitat, true);

            return $this->redirectToRoute('app_admin_habitat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitat/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_habitat_show', methods: ['GET'])]
    public function read(Habitat $habitat): Response
    {
        return $this->render('habitat/show.html.twig', [
            'habitat' => $habitat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_habitat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitat $habitat, HabitatRepository $repository): Response
    {
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->saveHabitat($habitat, true);

            return $this->redirectToRoute('app_admin_habitat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitat/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form,
            'mode'=> 'Modifier',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_habitat_delete', methods: ['POST'])]
    public function delete(Request $request, Habitat $habitat, HabitatRepository $repository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habitat->getId(), $request->request->get('_token'))) {
        $repository->removeHabitat($habitat);

        } else {

        }

        return $this->redirectToRoute('app_admin_habitat_index', [], Response::HTTP_SEE_OTHER);
        }
}
