<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnimalFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("animalFile", VichImageType::class, [
                "required" => false,
                "label" => "Avatar",
                "help" => "Fichiers autorisés: PNG, JPG, JPEG. Dimensions: 500x500. Taille max: 8MB.",
                "attr" => [
                    "accept" => ".png, .jpg, .jpeg",
                    "class"  => "p-0",
                ],
                "label_attr" => [
                    "class" => "d-block",
                ],
                "constraints" => [
                    new Assert\Image([
                        "minWidth" => 500,
                        "maxWidth" => 500,
                        "minHeight" => 500,
                        "maxHeight" => 500,
                        "maxSizeMessage" => "L'image doit respecter les dimensions suivantes : 500x500.",
                        "mimeTypes" => [
                            "image/jpeg",
                            "image/png",
                        ],
                        "mimeTypesMessage" => "Veuillez joindre un fichier au format JPG, JPEG, PNG.",
                    ]),
                ],
                "allow_delete" => true,
                "download_uri" => false,
                "image_uri" => false,
            ])
            ->add(
                'removeAnimalFile',
                HiddenType::class,
                [
                    'required' => false,
                    'mapped'   => false,
                    'attr' => [
                        'data-kt-image-input-delete-name' => 'userAvatarFile',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Image::class,
        ]);
    }
}
