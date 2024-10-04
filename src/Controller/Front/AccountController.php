<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AccountController extends AbstractController
{
    #[Route('/account/{id}', name: 'app_account_show')]
    public function edit(Request $request, User $user, UserRepository $userRepository, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-user' . $user->getId())->getValue();
      
        $form = $this->createForm(UserType::class, $user, [
            'is_new' => false,
            'is_edit' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $avatarFile = $form->get('avatar')->getdata();
           
            if($avatarFile != null) {
               $user->setAvatar($avatarFile);
            }

            $roles[]= $form->get('roles')->getdata();
            $user->setRoles($roles);

            $password = $form->get('plainpassword')->get('password')->getdata();
         
            if($password != null) {
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('plainpassword')->get('password')->getdata()));
            }
            
            $userRepository->saveUser($user, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/account/edit.html.twig', [
            'csrf_token'     => $csrfToken,
            'user'           => $user,
            'delete_btn'     => true,
            'form'           => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if($user->getDeletedAt()){
            return $this->redirectToRoute('app_home');
        }

        $submittedToken = $request->request->get('token');
     
        if ($this->isCsrfTokenValid('delete-user' .$user->getId(), $submittedToken)) {
            $userRepository->removeUser($user, true);

            $this->addFlash('success', 'Le compte a été supprimé avec succès.');
            return $this->redirectToRoute('app_home');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet user, veuillez réessayer.');
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{user}/remove-avatar', name: 'app_account_remove_avatar', methods: ['POST'])]
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