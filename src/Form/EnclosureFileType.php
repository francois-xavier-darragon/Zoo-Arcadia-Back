<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class EnclosureFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("EnclosureFile", FileType::class, [
                "required" => false,
                "label" => "Images",
                "help" => "Fichiers autorisés: PNG, JPG, JPEG. Dimensions: 750x500. Taille max: 2MB.",
                "attr" => [
                    "accept" => ".png, .jpg, .jpeg",
                    "class"  => "p-0",
                ],
                "label_attr" => [
                    "class" => "d-block",
                ],
                "constraints" => [
                    new Assert\Image([
                        "minWidth" => 750,
                        "maxWidth" => 750,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_class" => Image::class,
        ]);
    }
}
