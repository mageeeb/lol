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

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'required' => true,
                'attr' => ['min' => 0, 'max' => 20], // Par exemple, pour une note sur 20
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nom', // Affiche le champ `nom` de l'entité Matiere
                'label' => 'Matière',
                'placeholder' => 'Choisissez une matière',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}

//class NoteType extends AbstractType
//{
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $builder
//            ->add('matiere', EntityType::class, [
//                'class' => Matiere::class, // Spécifie la classe associée
//                'choice_label' => 'nom', // Utilise la propriété 'nom' de Matiere comme label dans le formulaire
//                'placeholder' => 'Sélectionnez une matière', // Ajoute un choix vide par défaut
//                'label' => 'Matière', // Libellé du champ
//            ])
//            ->add('note', NumberType::class, [
//                'label' => 'Note',
//                'attr' => ['min' => 0, 'max' => 20],
//            ]);
//    }
//
//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'data_class' => Note::class, // Le formulaire est lié à l'entité Note
//        ]);
//    }
//}