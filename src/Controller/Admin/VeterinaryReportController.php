<?php

namespace App\Controller\Admin;

use App\Entity\VeterinaryReport;
use App\Form\VeterinaryReportType;
use App\Repository\VeterinaryReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/veterinaryreports')]
class VeterinaryReportController extends AbstractController
{
    #[Route('/', name: 'app_admin_veterinaryreport_index', methods: ['GET'])]
    public function index(VeterinaryReportRepository $veterinaryreportRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $veterinaryreports = $veterinaryreportRepository->findAllveterinaryreport();
        $csrfTokens = [];

        foreach ($veterinaryreports as $veterinaryreport) {
            $csrfTokens[$veterinaryreport->getId()] = $csrfTokenManager->getToken('delete-veterinaryreport' . $veterinaryreport->getId())->getValue();
        }

        return $this->render('admin/veterinaryreport/index.html.twig', [
            'veterinaryreports' => $veterinaryreportRepository->findAllVeterinaryReport(),
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,
        ]);
    }

    #[Route('/new', name: 'app_admin_veterinaryreport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VeterinaryReportRepository $veterinaryreportRepository): Response
    {
        $veterinaryreport = new VeterinaryReport();
        $form = $this->createForm(VeterinaryReportType::class, $veterinaryreport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $veterinaryreportRepository->saveVeterinaryReport($veterinaryreport, true);

            return $this->redirectToRoute('app_admin_veterinaryreport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/veterinaryreport/edit.html.twig', [
            'veterinaryreport' => $veterinaryreport,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_veterinaryreport_show', methods: ['GET'])]
    public function read(VeterinaryReport $veterinaryreport, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-veterinaryreport' . $veterinaryreport->getId())->getValue();

        return $this->render('admin/veterinaryreport/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'veterinaryreport' => $veterinaryreport,
            'delete_btn' => true,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_veterinaryreport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VeterinaryReport $veterinaryreport, VeterinaryReportRepository $veterinaryreportRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-veterinaryreport' . $veterinaryreport->getId())->getValue();

        $form = $this->createForm(VeterinaryReportType::class, $veterinaryreport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $veterinaryreportRepository->saveVeterinaryReport($veterinaryreport, true);

            return $this->redirectToRoute('app_admin_veterinaryreport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/veterinaryreport/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'veterinaryreport' => $veterinaryreport,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_veterinaryreport_delete', methods: ['POST'])]
    public function delete(Request $request, VeterinaryReport $veterinaryreport, VeterinaryReportRepository $veterinaryreportRepository): Response
    {
        if($veterinaryreport->getDeletedAt()){
            return $this->redirectToRoute('app_admin_veterinaryreport_index');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-veterinaryreport'.$veterinaryreport->getId(), $submittedToken)) {
            $veterinaryreportRepository->removeVeterinaryReport($veterinaryreport, true);

            $this->addFlash('success', 'Le utilisateur "'.$veterinaryreport->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_veterinaryreport_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet veterinaryreport, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_veterinaryreport_index', [], Response::HTTP_SEE_OTHER);
    }
}
