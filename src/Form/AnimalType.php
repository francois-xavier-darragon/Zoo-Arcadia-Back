<?php

namespace App\Form;

use App\Entity\Animal;
use App\Form\AnimalFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('health')
            ->add('veterinaryReports')
            ->add('breed')
            ->add('habitat')
            ->add('image', AnimalFileType::class ,[
                'required' => false,
                'label' => 'Avatar',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                ],
            ])
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('deleted')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
