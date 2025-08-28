<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProjectValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'projectValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    // Correction: Changement cohérent vers ProjectAttribute
    #[ORM\ManyToOne(targetEntity: ProjectAttribute::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectAttribute $projectAttribute = null; // Renommer $attribute en $projectAttribute

    // ... Getters/Setters
    public function getId(): ?int { return $this->id; }
    
    public function getProject(): ?Project { return $this->project; }
    public function setProject(?Project $project): static 
    { 
        $this->project = $project;
        return $this;
    }
    
    public function getValue(): ?string { return $this->value; }
    public function setValue(string $value): static 
    { 
        $this->value = $value;
        return $this;
    }
    
    // Correction: Changement des méthodes pour utiliser ProjectAttribute
    public function getProjectAttribute(): ?ProjectAttribute 
    { 
        return $this->projectAttribute; 
    }
    
    public function setProjectAttribute(?ProjectAttribute $projectAttribute): static 
    { 
        $this->projectAttribute = $projectAttribute;
        return $this;
    }
    public function __construct()
{
    $this->value = ''; // Valeur par défaut
}
}