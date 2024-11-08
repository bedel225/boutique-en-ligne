<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prenom',
                'attr' => [
                    'placeholder' => 'votre prenom',
                ]
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'votre nom',
                ]
            ])
            ->add('address',TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'votre adresse',
                ]
            ])
            ->add('postal',TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => 'Votre code postale',
                ]
            ])
            ->add('city',TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'votre ville',
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'votre pays',
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Numero',
                'attr' => [
                    'placeholder' => 'votre numero de telephone',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=> "Ajouter l'adresse",
                'attr' => ['class' => 'btn btn-success']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
