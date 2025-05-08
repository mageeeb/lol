<?php

namespace App\Form;
use App\Entity\Matiere;
use App\Form\NoteType;
use App\Entity\Eleve;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez le nom'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner un nom.'
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'required' => true,
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez le prénom'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner un prénom.'
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'E-mail',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner un e-mail.'
                    ]),
                    new Assert\Email([
                        'message' => 'Veuillez entrer une adresse e-mail valide.'
                    ]),
                ],
            ])
            ->add('age', IntegerType::class, [
                'required' => true,
                'label' => 'Âge',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner un âge.',
                    ]),
                    new Assert\Range([
                        'min' => 1,
                        'max' => 120,
                        'notInRangeMessage' => 'L\'âge doit être compris entre {{ min }} et {{ max }} ans.'
                    ]),
                ],
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Masculin' => 'M',
                    'Féminin' => 'F'
                ]
            ])
            ->add('classe', ChoiceType::class, [
                'choices' => [
                    'Classe 1' => 'C1',
                    'Classe 2' => 'C2'
                ]
            ])
            // Ajout du bouton de soumission "save"
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Photo de profil',
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M', // Limite la taille à 2 Mo
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image au format valide (JPEG/PNG).',
                    ])
                ],
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class, // L’entité correspondante
                'choice_label' => 'nom',  // Affiche le champ `nom` dans la liste déroulante
                'placeholder' => 'Sélectionnez une matière',
                'required' => false,      // Facultatif si la jointure est nullable
            ])

            ->add('notes', CollectionType::class, [
                'entry_type' => NoteType::class, // Utilise NoteType pour chaque élément de la collection
                'allow_add' => true, // Autoriser l'ajout dynamique d'éléments
                'allow_delete' => true, // Autoriser la suppression
                'by_reference' => false, // Pour gérer correctement la relation OneToMany
                'label' => 'Notes',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}