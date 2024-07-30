<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserVisitorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('avatar', AvatarType::class ,[
            'required' => false,
            'label' => false,
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
            ],
        ])
        ->add('firstname', TextType::class, [
            'required' => true,
            'label' => 'Prénom',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('lastname', TextType::class, [
            'required' => true,
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('email', EmailType::class, [
            'required' => true,
            'label' => 'Adresse email',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
                'autocomplete' => 'new-email',
            ]
        ])
       ;

    if($options['is_new'] === true) {
        $builder
            ->add('plainpassword',
                ManagePasswordType::class,
                [
                    'mapped' =>false,
                ]
            );
    }

    if($options['is_edit'] === true) {
        $builder
            ->add('plainpassword',
                ManagePasswordType::class,
                [
                    'mapped' =>false,
                ]
            );
    }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new'     => false,
            'is_edit'    => false
        ]);
    }
}
