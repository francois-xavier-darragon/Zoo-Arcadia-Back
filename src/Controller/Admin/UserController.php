<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/admin/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-token');

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAllUser(),
            'csrfToken'     => $csrfToken->getValue(),
            'delete_btn'    => true
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles($form->get('roles')->getdata());
            $user->setPassword($passwordHasher->hashPassword($user, bin2hex(random_bytes(25))));
            $userRepository->saveUser($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function read(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'delete_btn' => true
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($user->getPassword()){

                $roles[]= $form->get('roles')->getdata();
                $user->setRoles($roles);
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
                $user->eraseCredentials();
            }

            $userRepository->saveUser($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if($user->getDeletedAt()){
            return $this->redirectToRoute('app_admin_user_index');
        }

        $submittedToken = $request->request->get('token');
        
        if ($this->isCsrfTokenValid('delete-user', $submittedToken)) {
            $userRepository->removeUser($user, true);

            $this->addFlash('success', 'Le utilisateur "'.$user->getLastName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_user_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet user, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
