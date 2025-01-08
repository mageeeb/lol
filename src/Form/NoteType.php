<?php

namespace App\Form;

use App\Entity\Note;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NoteType extends AbstractType
{
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $builder
//            ->add('matiere')
//            ->add('note');
//
//    }
//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            // Configure your form options here
//            'data_class' => Note::class,
//        ]);
//    }
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $builder
//            // Champ matiere, lié à l'entité Matiere
//            ->add('matiere', EntityType::class, [
//                'class' => \App\Entity\Matiere::class, // Assurez-vous que la classe existe
//                'choice_label' => 'nom', // Utilise la propriété "nom" pour afficher les options
//                'label' => 'Matière', // Label du champ
//                'placeholder' => 'Sélectionnez une matière', // Valeur par défaut
//            ])
//
//            // Champ note, numérique, avec limites min et max
//            ->add('note', NumberType::class, [
//                'label' => 'Note',
//                'attr' => [
//                    'min' => 0,
//                    'max' => 20,
//                ]
//            ]);
//    }
//
//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'data_class' => Note::class,
//        ]);
//    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ matière, lié à l'entité Matiere
            ->add('matiere', EntityType::class, [
                'class' => \App\Entity\Matiere::class, // Classe associée à l'entité Matiere
                'choice_label' => 'nom', // La propriété utilisée comme label dans les choix
                'label' => 'Matière',
                'placeholder' => 'Sélectionnez une matière', // Une valeur par défaut
            ])

            // Champ note, valeur numérique avec des bornes (min et max)
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'attr' => [
                    'min' => 0, // Valeur minimale
                    'max' => 20, // Valeur maximale
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class, // La classe de données associée
        ]);
    }

}
