<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Rapport vétérinaire',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
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
            'data_class' => Service::class,
        ]);
    }
}