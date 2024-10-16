<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ModifierPwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('actualPassword', PasswordType::class, [
                'label' => 'Votre mot de passe actuel',
                'attr'=> [
                    'placeholder' => 'Votre mot de passe actuel'
                ],
                'mapped' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 32
                    ])
                ],
                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'indiquer votre mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmer votre mot de passe'
                    ]
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label'=> "Modifier mon mot de passe",
                'attr' => ['class' => 'btn btn-success']
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];

                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                $isValid = $passwordHasher->isPasswordValid($user, $form->get('actualPassword')->getData());
                if (!$isValid) {
                    $form->get('actualPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme. Veuillez vérifier vatre saisir."));
                }


            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' =>  null
        ]);
    }
}
