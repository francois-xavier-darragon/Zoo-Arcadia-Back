<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Image;
use App\Form\UserType;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/users')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper): Response
    {
        $users = $userRepository->findAllUser();
        $csrfTokens = [];

        foreach ($users as $user) {
            $csrfTokens[$user->getId()] = $csrfTokenManager->getToken('delete-user' . $user->getId())->getValue();
        }

        $csrfToken = $csrfTokenManager->getToken('delete-user')->getValue();
    
        return $this->render('admin/user/index.html.twig', [
            'users'          => $users,
            'csrf_tokens'    => $csrfTokens,
            'csrf_token'     => $csrfToken,
            'delete_btn'     => true,
            'allRoles'       => User::ROLES,
            'uploaderHelper' => $uploaderHelper
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, UploaderHelper $uploaderHelper): Response
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
            'uploaderHelper' => $uploaderHelper,
            'user' => $user,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function read(User $user, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-user' . $user->getId())->getValue();
        
        return $this->render('admin/user/show.html.twig', [
            'uploaderHelper' => $uploaderHelper,
            'csrf_token'     => $csrfToken,
            'user'           => $user,
            'delete_btn'     => true,
            'allRoles'       => User::ROLES,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, CsrfTokenManagerInterface $csrfTokenManager, UploaderHelper $uploaderHelper): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-user' . $user->getId())->getValue();
      
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roles[]= $form->get('roles')->getdata();
            $user->setRoles($roles);
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            $userRepository->saveUser($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'uploaderHelper' => $uploaderHelper,
            'csrf_token'     => $csrfToken,
            'user'           => $user,
            'delete_btn'     => true,
            'form'           => $form,
            'mode'           => 'Modifier',
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

    #[Route('/{user}/remove-avatar', name: 'app_admin_user_remove_avatar', methods: ['POST'])]
    public function removeAvatar(User $user, UserRepository $userRepository, ImageRepository $imageRepository): JsonResponse
    {
        $image = $user->getAvatar();
        if ($image) {
            $image = $imageRepository->findOneById($user->getAvatar());
            $user->setAvatar(null);
            $userRepository->saveUser($user, true);
            $imageRepository->removeImage($image, true);
            return new JsonResponse(['status' => 'success'], 200);
        }
        return new JsonResponse(['status' => 'error', 'message' => 'No avatar to remove'], 400);
    }

}
