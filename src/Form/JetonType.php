<?php

namespace App\Form;

use App\Entity\Jeton;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class JetonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'CREATE' => 'CREATE' ,
                    'READ' => 'READ',
                    'UPD' => 'UPD',
                    'DEL' => 'DEL'
                ],
                'label' => "Choisir l'opération",
                'expanded' => false,
                'multiple' => false
            ])
            
            ->add('user', EntityType::class, [
            // looks for choices from this entity
                'class' => User::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'email',

                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
                'label' => "Ce jeton permet d'authentifier quels utilisateur ?"
            ])
            ->add('submit', SubmitType::class,[
            'label' => "Créer un jeton"     
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Jeton::class,
        ]);
    }
}
