<?php

namespace App\Entity;
use App\Entity\User;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks] // ← N'oublie pas cette annotation
class Project
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ARCHIVED = 'archived';   

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du projet ne peut pas être vide")]
    #[Assert\Length(
    max: 255,
    maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères"
)]
private ?string $name = null;

    #[ORM\OneToMany(targetEntity: ProjectValue::class, mappedBy: 'project', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $projectValues;


    // #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?User $user = null;


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(groups: ['admin'])] // ← Validation conditionnelle
    private ?User $user = null;


     /**
     * The project status (active/completed/archived)   
     * 
     * @var string|null
     */
    #[ORM\Column(length: 20)]
    #[Assert\Choice([self::STATUS_ACTIVE, self::STATUS_COMPLETED, self::STATUS_ARCHIVED])]
    #[Assert\NotNull(groups: ['admin'])] // ← Validation conditionnelle
    private ?string $status = self::STATUS_ACTIVE;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(max: 2000)]
    private ?string $feedback = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PreUpdate]
    public function setTimestampOnUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }


    public function __construct()
    {
        $this->projectValues = new ArrayCollection();

        
    }

    #[ORM\PrePersist]
    public function initializeProject(): void
    {
        // Définir les timestamps
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        
        // Définir le statut par défaut
        $this->status = self::STATUS_ACTIVE;
        
        // Ajouter une valeur vide si nécessaire
        if ($this->projectValues->isEmpty()) {
            $projectValue = new ProjectValue();
            $projectValue->setProject($this);
            $this->addProjectValue($projectValue);
        }
}


    #[ORM\PreUpdate] 
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }


/**
 * @return Collection<int, ProjectValue>
 */
public function getProjectValues(): Collection 
{
    return $this->projectValues;
}

public function addProjectValue(ProjectValue $value): self
{
    if (!$this->projectValues->contains($value)) {
        $this->projectValues[] = $value;
        $value->setProject($this);
    }
    return $this;
}

public function removeProjectValue(ProjectValue $value): self
{
    if ($this->projectValues->removeElement($value)) {
        if ($value->getProject() === $this) {
            $value->setProject(null);
        }
    }
    return $this;
}





    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
        public function getStatusClass(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_ARCHIVED => 'secondary',
            default => 'light',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'En cours',
            self::STATUS_COMPLETED => 'Terminé',
            self::STATUS_ARCHIVED => 'Archivé',
            default => 'Inconnu',
        };
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }
    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(?string $feedback): static
    {
        $this->feedback = $feedback;
        return $this;
    }
    public function __toString(): string
{
    return $this->name ?? 'Nouveau projet';
}
public function isActive(): bool
{
    return $this->status === self::STATUS_ACTIVE;
}
}