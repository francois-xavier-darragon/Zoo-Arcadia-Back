<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManagePasswordType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent correspondre.',
            'required' => true,
            'help_attr' => [
                'class' => 'my-2'
            ],
            'help_html' => true,
            'first_options'  => [
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label fw-semibold fs-6',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg form-control-solid password-control',
                    'autocomplete' => 'new-password',
                ],
                'help_attr' => [
                    'class' => 'my-2 fst-italic text-mute'
                ]
            ],
            'second_options' => [
                'label' => 'Confirmer',
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label fw-semibold fs-6',
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg form-control-solid',
                    'autocomplete' => 'new-password',
                ],
                'help_attr' => [
                    'class' => 'my-2 fst-italic text-mute'
                ]
            ],
        ]
    );
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}