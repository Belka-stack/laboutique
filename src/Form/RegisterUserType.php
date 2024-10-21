<?php

// Ce fichier contient tout ce qui va permmetre à symfony de créer un formulaire.

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class RegisterUserType extends AbstractType
{

    // A l'intérieur de la classe on a une méthode qui construit le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // A l'intérieur de la méthode on va le builder($builder)qui est en fait FormBuilderInterface.C'est un mécanisme d'injection de dépendance.Et dans ce builder on vient ajouter plusieurs éléments(->add..).Qui correspond au propriété de notre entité(table) User.
        $builder
            ->add('firstname', TextType::class, [
                'label' => "Votre prénom",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                    ])
                    ],
                'attr' => [
                    'placeholder' => "Indiquer votre prénom"
                ]

            ])

            ->add('lastname', TextType::class, [
                'label' => "Votre nom",
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                    ])
                ],
                'attr' => [
                    'placeholder' => "Indiquer votre nom "

                ]
            ])

            ->add('email', EmailType::class, [
                'label' => "Votre adresse email",
                'attr' => [
                    'placeholder' => "Indiquer votre adresse email"
                ]
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 30
                    ])
                ],
                'first_options'  => [
                    'label' => 'Votre mot de passe', 
                    'attr' => [
                    'placeholder' => "Indiquer votre mot de passe",
                    ],
                    'hash_property_path' => 'password'
            ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe',
                    'attr' => [
                    'placeholder' => "Confirmer votre mot de passe",
                    ]
                    ],
                    'mapped' => false,
            ])

            ->add('submit', SubmitType::class, [
                'label' => "Valider",
                'attr' => [
                    'class' => 'btn'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email'
                ])
                ],
            'data_class' => User::class,
        ]);
    }
}
