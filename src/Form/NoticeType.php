<?php

namespace App\Form;

use App\Entity\Notice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoticeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isvisible', ChoiceType::class, [
                'mapped' => false,
                'label' => false,
                'choices' => array_flip(NOTICE::STATUT),
                    'label_attr' => [
                        'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-solid',
                        'data-placeholder' => 'Choisir une race existante'
                    ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notice::class,
        ]);
    }
}
