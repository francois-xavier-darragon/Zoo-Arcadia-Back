<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Breed;
use App\Entity\Enclosure;
use App\Entity\Habitat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', CollectionType::class ,[
                'entry_type' => AnimalFileType::class,
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
                'label' => 'Nom',
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
            ->add('habitat', EntityType::class, [
                'class'=> Habitat::class,
                'required' => true,
                'label' => 'Habitat',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
                ],
                'attr' => [
                    'class' => 'form-control form-control-solid',
                ]
            ])
            ->add('enclosure', EntityType::class, [
                'class'=> Enclosure::class,
                'required' => true,
                'label' => 'enclos',
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
