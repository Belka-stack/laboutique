<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => "Votre mot de passe actuel",
                'attr' => [
                'placeholder' => "Enter votre mot de passe actuel",
                ],
                
                'mapped' => false
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
                'label' => "Mettre à jour mon mot de passe",
                'attr' => [
                    'class' => 'btn btn-success'
                ]

            ])

            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){

            // Récupération du formulaire

                $form =$event->getForm(); 
                $user = $form->getConfig()->getOptions()['data'];
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];

            //1.Récupérer le mot de passe saisi par l’utilisateur et le comparer au mdp en BDD.Il sera à true s'il est valid et false si ce n'est pas le cas.

                $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $form->get('actualPassword')->getData()
                );
                
            

            // 3. Si c’est != envoyer une erreur

            if (!$isValid) {
                // On va chercher notre formulaire,puis le champs qui est concerné par l'erreur,en l'occurence actual password et on lui la méthode addError() qui permet d'ajouter des erreurs à la volée à des input de notre formulaire.Et elle accepte en paramètre un nouvelle objet FormError qui délivre un message d'erreur si le mot de passe n'est pasconforme.

                $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme.Veuillez saisir votre ancien mot de passe."));
            }



            })
        
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
