<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Votre adresse email',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
                ],
                'attr' => [
                    'class' => 'form-control form-control-solid',
                    'autocomplete' => 'new-email',
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
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
        ]);
    }
}
