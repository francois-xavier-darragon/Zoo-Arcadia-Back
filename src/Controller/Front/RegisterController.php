<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserVisitorType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegisterController extends AbstractController
{
    
    #[Route('/signin', name: 'app_signin')]
    public function register(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserVisitorType::class, $user, [
            'is_new'  => true,
            'is_edit' => false
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $avatarFile = $form->get('avatar')->getdata();
            if($avatarFile != null) {
               $user->setAvatar($avatarFile);
            }

            $roles[] = "ROLE_VISITOR";
            $user->setRoles($roles);
          
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('plainpassword')->get('password')->getdata()));
            $userRepository->saveUser($user, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/register/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'mode' => 'Ajouter',
        ]);
    }
}
