<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('street', TextType::class, [
            'label' => 'N° et Rue',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('complement', TextType::class, [
            'label' => 'Complément',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('zip', TextType::class, [
            'label' => 'Code postal',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
    ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}