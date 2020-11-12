<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
            'label' => "Votre email"     
        ])
            ->add('password', PasswordType::class,[
            'label' => "Mot de passe"     
        ])
            ->add('passwordconf', PasswordType::class,[
            'label' => "Confirmer le mot de passe"     
        ])

            ->add('prenom', TextType::class,[
            'label' => "Votre prénom"     
        ])
            ->add('nom', TextType::class,[
            'label' => "Votre nom"     
        ])
            ->add('submit', SubmitType::class,[
            'label' => "Crér un compte"     
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
