<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
#[ORM\Table(name: "eleve")]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (
        type: 'integer',
        options: ['unsigned' => true],
    )]
    private ?int $id;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(
        type: 'smallint',
        options: [
            'unsigned' => true,
            'comment' => 'L\'âge de l\'élève',
        ],
    )]
    private ?int $age = null;

    #[ORM\Column(length: 1)]
    private ?string $sexe = null;

    // ici, il faudra une jointure avec la table Classe
    #[ORM\Column(
        length: 25,
        options: [
            'comment' => 'Devra être une référence à une entité Classe',
        ],
    )]
    private ?string $classe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'eleve', cascade: ['persist', 'remove'])]
    private Collection $notes;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

//    #[ORM\ManyToOne(targetEntity: Matiere::class)]
//    #[ORM\JoinColumn(nullable: true)] // nullable: true si une matière peut ne pas être assignée
//    private ?Matiere $matiere = null;
    #[ORM\ManyToOne(targetEntity: Matiere::class)]
    #[ORM\JoinColumn(name: 'matiere_id', referencedColumnName: 'id', nullable: true)]
    private ?Matiere $matiere = null;


    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): static
    {
        $this->classe = $classe;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setEleve($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            if ($note->getEleve() === $this) {
                $note->setEleve(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }
}
