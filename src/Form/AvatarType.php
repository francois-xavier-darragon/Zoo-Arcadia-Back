<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("userAvatarFile",
            VichImageType::class,
            [
                'required'   => true,
                'label'      => false,
                'help'       => 'Ce fichier doit obligatoirement être au format JPG, JPEG, PNG.<br>L\'image doit respecter les dimensions suivantes : 500px X 500px.',
                'help_attr'  => [
                    'class' => 'my-2 fst-italic',
                ],
                'help_html'  => true,
                'constraints' => [
                    new ImageConstraint([
                        'minWidth' => '500',
                        'maxWidth' => '500',
                        'minHeight' => '500',
                        'maxHeight' => '500',
                        'maxSizeMessage' => 'L\'image doit respecter les dimensions suivantes : 500px X 500px.',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez joindre un fichier au format JPG, JPEG, PNG.',
                    ])
                ],
                // VichUploader config
                'allow_delete'    => false,
                'download_uri'    => false,
                'image_uri'       => false,
                
            ])
            ->add(
                'removeUserAvatarFile',
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
