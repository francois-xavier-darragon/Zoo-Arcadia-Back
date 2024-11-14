<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Entity\Breed;
use App\Entity\VeterinaryReport;
use App\Form\AnimalFileType;
use App\Form\AnimalType;
use App\Form\VeterinaryReportType;
use App\Repository\AnimalRepository;
use App\Repository\BreedRepository;
use App\Repository\ImageRepository;
use App\Repository\VeterinaryReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/admin/animals')]
class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_admin_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $animals = $animalRepository->findAnimalBy(['deleted_At'=> null]);
        $csrfTokens = [];

        foreach ($animals as $animal) {
            $csrfTokens[$animal->getId()] = $csrfTokenManager->getToken('delete-animal' . $animal->getId())->getValue();
        }

        return $this->render('admin/animal/index.html.twig', [
            'animals' => $animals,
            'csrf_tokens'    => $csrfTokens,
            'delete_btn'    => true,
        ]);
    }

    #[Route('/new', name: 'app_admin_animal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnimalRepository $animalRepository, BreedRepository $breedRepository): Response
    {
        $breeds = $breedRepository->findBreedBy(['deleted_At'=> null]);
        $countBreeds = count($breeds) === 0;
       
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal, [
            'countBreeds' => $countBreeds
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formBreddData = $form->get('addbreed')->getData();

            if($formBreddData != null){
                $breed = new Breed;
                
                $breed->setName(ucfirst($formBreddData));
                $breedRepository->saveBreed($breed, true);
                $animal->setBreed($breed);
            
            }
            
            $animalRepository->saveAnimal($animal, true);

            return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
            'mode' => 'Ajouter',
            'countBreeds' => $countBreeds,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_animal_show', methods: ['GET'])]
    public function read(Request $request, Animal $animal, AnimalRepository $animalRepository,  CsrfTokenManagerInterface $csrfTokenManager, TokenStorageInterface $tokenStorage): Response
    {
        $csrfToken = $csrfTokenManager->getToken('delete-animal' . $animal->getId())->getValue();
        $csrfTokenVeterinaryReport = $csrfTokenManager->getToken('delete-veterinaryReport' . $animal->getId())->getValue();
        $roles = $this->getRole($tokenStorage);

        $form = $this->createForm(VeterinaryReportType::class, $animal, [
            'roles' => $roles
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $animalRepository->saveAnimal($animal, true);

            return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/animal/show.html.twig', [
            'form' => $form,
            'csrf_token'  => $csrfToken,
            'csrf_token_VeterinaryReport' => $csrfTokenVeterinaryReport,
            'animal' => $animal,
            'delete_btn' => true,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, AnimalRepository $animalRepository, CsrfTokenManagerInterface $csrfTokenManager, BreedRepository $breedRepository, UploaderHelper $uploaderHelper): Response
    {
        //Recovery of the CSRF token for secure deletion
        $csrfToken = $csrfTokenManager->getToken('delete-animal' . $animal->getId())->getValue();
    
        //Animal Breed Management
        $breeds = $breedRepository->findBreedBy(['deleted_At'=> null]);
        $countBreeds = count($breeds) === 0;
        $images = $animal->getImages();

        //Managing existing images
        $existingImages = [];

        $reflectionClass = new \ReflectionClass($animal);

        $entitiName = strtolower($reflectionClass->getShortName()).'s';
        foreach ($images as $image) {

            $path =  '/uploads/images/'. $entitiName .'/'. $image->getName();
            $existingImages[] = [
                'id' => $image->getId(),
                'path' => $path
            ];
        }
      
        $form = $this->createForm(AnimalType::class, $animal, [
            'countBreeds' => $countBreeds
        ]);

       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formBreddData = $form->get('addbreed')->getData();

            // Création d'une nouvelle race si nécessaire
            if($formBreddData != null){
                $breed = new Breed;
                $breed->setName(ucfirst($formBreddData));
                $breedRepository->saveBreed($breed, true);
                $animal->setBreed($breed);
            }

            $animalRepository->saveAnimal($animal, true);

            return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/animal/edit.html.twig', [
            'csrf_token'  => $csrfToken,
            'animal' => $animal,
            'form' => $form,
            'mode'=> 'Modifier',
            'delete_btn' => true,
            'countBreeds' => $countBreeds,
            'uploaderHelper' => $uploaderHelper,
            'existingImages' => json_encode($existingImages)
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

            $animal->setDeletedAt(new \DateTimeImmutable());
            $animalRepository->saveAnimal($animal, true);

            $this->addFlash('success', 'L\'animal "'.$animal->getName().'" a été supprimé avec succès.');
            return $this->redirectToRoute('app_admin_animal_index');
        }

        $this->addFlash('error', 'Un problème est survenu lors de la suppression de cet animal, veuillez réessayer.');
        return $this->redirectToRoute('app_admin_animal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add-image-field', name: 'app_add_image_field')]
    public function addImageField(Request $request): Response
    {
        $form = $this->createForm(AnimalFileType::class, null, [
            'mapped' => false,
            'label' => false,
            'required' => false,
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
            ],
        ]);

        return $this->render('_include/_components/_forms/_image-upload-input.html.twig', [
            'input' => $form->get('image'),
            'inputFile' => $form->get('image')->get('animalFile'),
            'removeFile' => $form->get('image')->get('animalFile'),
            'label' => $form->get('image'),
            'editButtonId' => 'edit-image-button-' . uniqid(),
            'removeButtonId' => 'remove-image-button-' . uniqid(),
            'uploaderHelper' => 'uploaderHelper',
            'baliseImg' => 'balise-img'
        ]);
    }

    #[Route('/{animal}/remove-animal-image/', name: 'app_admin_animal_remove_image', methods: ['POST'])]
    public function removeAnimalImage(Request $request, Animal $animal, AnimalRepository $animalRepository, ImageRepository $imageRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $imageId = ($data['imgId']) ?? null;

        if ($imageId === null) {
            return new JsonResponse(['status' => 'error', 'message' => 'ID de l\'image manquant'], 400);
        }
     
        $image = $imageRepository->findOneById($imageId);

        if (!$image) {
            return new JsonResponse(['status' => 'error', 'message' => 'Image non trouvée'], 404);
        }

        $animal->removeImage($image);
        $animalRepository->saveAnimal($animal, true);

        $imageRepository->removeImage($image, true);

        return new JsonResponse(['status' => 'success'], 200);
    }

    public function getRole($tokenStorage) {
        $roles = null;
        $token = $tokenStorage->getToken();
        
        if ($token != null) {

            $user = $token->getUser();

            if ($user instanceof UserInterface) {
                $roles = $user->getRoles();
            }
        }
        return $roles;
    }
}
