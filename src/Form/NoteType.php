<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Note;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

//class NoteType extends AbstractType
//{
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $builder
//            // Champ matière, lié à l'entité Matiere
//            ->add('matiere', EntityType::class, [
//                'class' => \App\Entity\Matiere::class, // Classe associée à l'entité Matiere
//                'choice_label' => 'nom', // La propriété utilisée comme label dans les choix
//                'label' => 'Matière',
//                'placeholder' => 'Sélectionnez une matière', // Une valeur par défaut
//            ])
//
//            // Champ note, valeur numérique avec des bornes (min et max)
//            ->add('note', NumberType::class, [
//                'label' => 'Note',
//                'attr' => [
//                    'min' => 0, // Valeur minimale
//                    'max' => 20, // Valeur maximale
//                ]
//            ]);
//    }
//
//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'data_class' => Note::class, // La classe de données associée
//        ]);
//    }
//
//}

//-----------------------------------------------------
//class NoteType extends AbstractType
//{
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $builder
//            ->add('eleve', EntityType::class, [
//                'class' => \App\Entity\Eleve::class, // Classe associée à l'entité Eleve

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class, // Spécifie la classe associée
                'choice_label' => 'nom', // Utilise la propriété 'nom' de Matiere comme label dans le formulaire
                'placeholder' => 'Sélectionnez une matière', // Ajoute un choix vide par défaut
                'label' => 'Matière', // Libellé du champ
            ])
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'attr' => ['min' => 0, 'max' => 20],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class, // Le formulaire est lié à l'entité Note
        ]);
    }
}