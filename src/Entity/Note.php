<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(
        type: 'integer',
        options: ['unsigned' => true],
    )]
    private ?int $id = null;

    // devra être une référence à une entité Matiere (nouvelle table)
    #[ORM\Column(
        type:'string',
        length: 50,
        options: ['default' => ''],
    )]
    private ?string $matiere = null;

    #[ORM\Column(
        type: 'float',
        options: ['unsigned' => true],
    )]
    private ?float $note = null;

    #[ORM\ManyToOne(targetEntity: Eleve::class, inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Eleve $eleve = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): static
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): static
    {
        $this->eleve = $eleve;

        return $this;
    }
}
