<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('image', UserAvatarType::class,[
        //     'required' => false,
        //     'label' => 'Avatar',
        //     'label_attr' => [
        //         'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
        //     ],
        //     "constraints" => [
        //         new Valid(),
        //     ]
        // ])
        ->add('email', EmailType::class, [
            'required' => true,
            'label' => 'Adresse email',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
                'autocomplete' => 'new-email',
            ]
        ])
        ->add('firstname', TextType::class, [
            'required' => true,
            'label' => 'Prénom',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('lastname', TextType::class, [
            'required' => true,
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label required fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
            ]
        ])
        ->add('roles', ChoiceType::class, [
            'mapped' => false,
           
            'label' => 'Role de l\'utilasteur',
            'choices'=> array_flip(USER::ROLES),
            'label_attr' => [
                'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
            ],
            'attr' => [
                'class' => 'form-control form-control-solid',
                'data-placeholder' => 'Choisir un client existant'
            ],
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent correspondre.',
            'required' => false,
            'help' => 'Laissez vide pour ne pas modifier votre mot de passe.',
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
                'help' => 'Laissez vide pour ne pas modifier votre mot de passe',
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
                'help' => 'Laissez vide pour ne pas modifier votre mot de passe',
                'help_attr' => [
                    'class' => 'my-2 fst-italic text-mute'
                ]
            ],
        ])
        // ->add(
        //     'phone',
        //     PhoneNumberType::class,
        //     [
        //         'required'                  => false,
        //         'label'                     => 'Téléphone',
        //         'label_attr' => [
        //             'class' => 'col-lg-4 col-form-label fw-semibold fs-6',
        //         ],
        //         'widget'                    => PhoneNumberType::WIDGET_COUNTRY_CHOICE,
        //         'format'                    => PhoneNumberFormat::INTERNATIONAL,
        //         'preferred_country_choices' => ['FR'],
        //         'country_options'           => [
        //             'attr' => [
        //                 'data-control' => 'select2',
        //                 'class' => 'form-control form-control-solid'
        //             ],
        //         ],
        //         'number_options'            => [
        //             'attr' => [
        //                 'class' => 'form-control form-control-solid',
        //                 'placeholder' => '0609080706'
        //             ],
        //         ],
        //     ]
        // )
        // ->add(
        //     'address',
        //     AddressUserType::class,
        //     [
        //         'required'    => true,
        //         'label'       => 'Adresse',
        //         'constraints' => [
        //             new Valid(),
        //         ],
        //     ]
        // )
    ;
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
