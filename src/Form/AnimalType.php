<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Breed;
use App\Entity\Habitat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('image', AnimalFileType::class ,[
            //     'mapped' => false,
            //     'label' => false,
            //     'required' => false,
            //     'label_attr' => [
            //         'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
            //     ],
            // ])
            ->add('images', CollectionType::class ,[
                'entry_type' => AnimalFileType::class,
                'label' => false,
                'allow_add' => true,
                // 'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'required' => false,
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                ],
            ])
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
            ->add('health', TextType::class, [
                'required' => true,
                'label' => 'Etat de Santé',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
                ],
                'attr' => [
                    'class' => 'form-control form-control-solid',
                ]
            ])
            ->add('addbreed', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Ajouter une race',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                ],
                'attr' => [
                    'class' => 'form-control form-control-solid',
                ]
            ])
            ->add('veterinaryReports', TextareaType::class, [
                'mapped' => false,
                'label' => 'Rapport vétérinaire',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
                ],
                'attr' => [
                    'class' => 'form-control form-control-solid',
                ]
            ])
            ->add('habitat', EntityType::class, [
                'class'=> Habitat::class,
                'required' => true,
                'label' => 'habitat',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
                ],
                'attr' => [
                    'class' => 'form-control form-control-solid',
                ]
            ])
        ;

        $countBreeds = $options['countBreeds'];
        if(!$countBreeds){
        $builder
            ->add('breed', EntityType::class, [
                    'label' => 'Race',
                    'class'=> Breed::class,
                    'choice_label' => 'name',
                    'label_attr' => [
                        'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-solid',
                        'data-placeholder' => 'Choisir une race existante'
                    ],
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
            'countBreeds' => false,
        ]);
    }
}
