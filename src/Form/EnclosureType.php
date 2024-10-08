<?php

namespace App\Form;

use App\Entity\Enclosure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnclosureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('images', CollectionType::class ,[
            'entry_type' => EnclosureFileType::class,
            'label' => false,
            'allow_add' => true,
            'by_reference' => false,
            'prototype' => true,
            'required' => false,
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
            ],
        ])
        ->add('name', TextType::class, [
            'required' => true,
            'label' => 'Nom de l\'enclos',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('shortdescription', TextType::class, [
            'required' => true,
            'label' => 'Description courte',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('description', TextareaType::class, [
            'required' => true,
            'label' => 'Description de l\'enclos',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enclosure::class,
        ]);
    }
}
