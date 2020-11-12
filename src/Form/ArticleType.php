<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
            'label' => "Le TITRE DE VOTRE ARTICLE"])
            ->add('contenu', TextareaType::class, [
            'label' => "LE CONTENU DE VOTRE ARTICLE"])
            // ->add('fonctionnalitesRenting', EntityType::class, [
            //     'required' => false,
            //     'label' => false,
            //     'class' => Fonctionnalite::class,
            //     'choice_label' => 'fonctionnalite',
            //     'multiple' => true
            // ])
            ->add('categories', EntityType::class, [
    
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'label' => "CHOIX DES CATEGORIE DE L'ARTICLE" ])
            ->add('submit', SubmitType::class,[
            'label' => "Ajouter un article"     
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
