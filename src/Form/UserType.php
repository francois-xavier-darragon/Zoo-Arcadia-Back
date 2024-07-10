<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AddressUserType;
use App\Form\AvatarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('avatar', AvatarType::class ,[
                'required' => false,
                'label' => false,
                'label_attr' => [
                    'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                ],
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
                    'data-placeholder' => 'Choisir un role existant'
                ],
            ])
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
            ->add(
                'address',
                AddressUserType::class,
                [
                    'label'=> 'Adresse',
                    'label_attr' => [
                        'class' => 'col-lg-4 fw-semibold fs-6 mt-2'
                    ]
                ]
            )
            ->add(
                'phone',
                IntegerType::class,
                [
                    'required' => false,
                    'label' => 'Téléphone',
                    'label_attr' => [
                        'class' => 'col-lg-4 col-form-label fw-semibold fs-6'
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-solid',
                    ]
                ]
            );

        if($options['is_new'] === true) {
            $builder
                ->add('plainpassword',
                    ManagePasswordType::class,
                    [
                        'mapped' =>false,
                    ]
                );
        }

        if($options['is_edit'] === true) {
            $builder
                ->add('plainpassword',
                    ManagePasswordType::class,
                    [
                        'mapped' =>false,
                    ]
                );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new'     => false,
            'is_edit'    => false
        ]);
    }
}