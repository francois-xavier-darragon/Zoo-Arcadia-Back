<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Image;
use App\Form\UserType;
use App\Repository\ImageRepository;
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
        $users = $userRepository->findAllUser();
        $csrfTokens = [];

        foreach ($users as $user) {
            $csrfTokens[$user->getId()] = $csrfTokenManager->getToken('delete-user' . $user->getId())->getValue();
        }

        return $this->render('admin/user/index.html.twig', [
            'users'         => $users,
            'csrf_Tokens'    => $csrfTokens,
            'delete_btn'    => true,
            'allRoles'      => User::ROLES,
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles[]= $form->get('roles')->getdata();
            $user->setRoles($roles);
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getdata()));
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
    public function read(User $user, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-user' . $user->getId())->getValue();
        
        return $this->render('admin/user/show.html.twig', [
            'csrf_token'  => $csrfToken,
            'user'        => $user,
            'delete_btn'  => true,
            'allRoles'    => User::ROLES,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, ImageRepository $imageRepository, UserPasswordHasherInterface $passwordHasher, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-user' . $user->getId())->getValue();
      
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($user->getPassword()){
                $image = $form->get('avatar')->getData();
                if ($image instanceof Image) {
                    $name = $image->getName();
                    if($name === null) {
                        $image->setDeletedAt(new \DateTimeImmutable());
                        $imageRepository->saveImage($image, true);
                        $user->setAvatar(null);
                    }
                }

                $roles[]= $form->get('roles')->getdata();
                $user->setRoles($roles);
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
                $user->eraseCredentials();
            }

            $userRepository->saveUser($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'user'        => $user,
            'form'        => $form,
            'mode'        => 'Modifier',
            'delete_btn'  => true
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if($user->getDeletedAt()){
            return $this->redirectToRoute('app_admin_user_index');
        }

        $submittedToken = $request->request->get('token');
     
        if ($this->isCsrfTokenValid('delete-user' .$user->getId(), $submittedToken)) {
            $userRepository->removeUser($user, true);

            $this->addFlash('success', 'Le utilisateur "'.$user->getLastName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_user_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet user, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
