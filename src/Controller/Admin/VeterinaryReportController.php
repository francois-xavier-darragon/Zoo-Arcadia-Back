<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\VeterinaryReport;
use App\Repository\AnimalRepository;
use App\Repository\VeterinaryReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/animals/{id}/veterinaryreports')]
class VeterinaryReportController extends AbstractController
{
    #[Route('/new', name: 'app_admin_veterinaryreport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Animal $animal, VeterinaryReportRepository $veterinaryreportRepository, AnimalRepository $animalRepository): Response
    {
        $datas = json_decode($request->getContent(), true);

        if (!$datas) {
            return new Response('Données invalides', Response::HTTP_BAD_REQUEST);
        }

        $health = intval($datas['health']) ;
        $healths = Animal::HEALTH;

        $checkHealth = $healths[$health];
        $animal->setHealth($checkHealth);

        $veterinaryReport = new VeterinaryReport();
        $veterinaryReport->setDetail($datas['veterinaryReport']);

        $animal->addVeterinaryReport($veterinaryReport);
        $veterinaryreportRepository->saveVeterinaryReport($veterinaryReport, true);
        
        $animal->setUpdatedAt($veterinaryReport->getUpdatedAt());
        $animalRepository->saveAnimal($animal, true);
       
        return new Response(json_encode([
            'status' => 'success',
            'health' => $checkHealth,
            'date' => $veterinaryReport->getCreatedAt()->format('d/m/Y'),
            'veterinaryReport' => $veterinaryReport->getDetail(),
        ]), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{veterinaryreport}', name: 'app_admin_veterinaryreport_show', methods: ['GET'])]
    public function read(VeterinaryReport $veterinaryreport, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-veterinaryreport' . $veterinaryreport->getId())->getValue();

        return $this->render('admin/veterinaryreport/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'veterinaryreport' => $veterinaryreport,
            'delete_btn' => true,
        ]);
    }

    #[Route('/edit', name: 'app_admin_veterinaryreport_edit', methods: ['POST'])]
    public function edit(Request $request, Animal $animal, VeterinaryReportRepository $veterinaryreportRepository, AnimalRepository $animalRepository): Response
    {

        $datas = json_decode($request->getContent(), true);

        if (!$datas) {
            return new Response('Données invalides', Response::HTTP_BAD_REQUEST);
        }

        $health = intval($datas['health']) ;
        $healths = Animal::HEALTH;

        $checkHealth = $healths[$health];
        $animal->setHealth($checkHealth);

        $veterinaryReportId = $datas['reportId'];
        $veterinaryReport = $veterinaryreportRepository->findOneById($veterinaryReportId);
        
        $veterinaryReport->setDetail($datas['veterinaryReport']);

        $veterinaryreportRepository->saveVeterinaryReport($veterinaryReport, true);
      
        $animal->setUpdatedAt($veterinaryReport->getUpdatedAt());
        $animalRepository->saveAnimal($animal, true);
       
        return new Response(json_encode([
            'status' => 'success',
            'health' => $checkHealth,
            'date' => $veterinaryReport->getUpdatedAt()->format('d/m/Y'),
            'veterinaryReport' => $veterinaryReport->getDetail(),
        ]), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/{veterinaryreport}/delete', name: 'app_admin_veterinaryreport_delete', methods: ['POST'])]
    public function delete(Request $request, VeterinaryReport $veterinaryreport, VeterinaryReportRepository $veterinaryreportRepository): Response
    {
        if($veterinaryreport->getDeletedAt()){
            $this->addFlash('error', 'Ce rapport a déjà été supprimé.');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-veterinaryreport'.$veterinaryreport->getId(), $submittedToken)) {
            $veterinaryreportRepository->removeVeterinaryReport($veterinaryreport, true);

            $this->addFlash('success', 'Le rapport a été supprimé avec succès.');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet veterinaryreport, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_veterinaryreport_index', [], Response::HTTP_SEE_OTHER);
    }
}
