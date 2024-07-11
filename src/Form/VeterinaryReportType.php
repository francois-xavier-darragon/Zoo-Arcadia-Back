<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\VeterinaryReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VeterinaryReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if(in_array('ROLE_VETERINARY',$options['roles'])){
            $builder
                ->add('health', ChoiceType::class, [
                    'label' => false,
                    'choices' => array_flip(Animal::HEALTH),
                    'label_attr' => [
                       'class' => 'col-lg-4 col-form-label fw-semibold'
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-solid',
                    ]
                ])
                ->add('veterinaryReports', TextareaType::class, [
                    'mapped' => false,
                    'label' => false,
                    'label_attr' => [
                        'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-solid',
                    ]
                ]
            );
        }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => VeterinaryReport::class,
            "data_class" => Animal::class,
            'roles' => null
        ]);
    }
}
