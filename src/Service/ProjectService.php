<?php
namespace App\Service;
// src/Service/ProjectService.php
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function updateStatus(Project $project, string $status): void
    {
        if (!in_array($status, [Project::STATUS_ACTIVE, Project::STATUS_COMPLETED, Project::STATUS_ARCHIVED])) {
            throw new \InvalidArgumentException('Statut invalide');
        }

        $project->setStatus($status);
        $this->entityManager->flush();
    }

    public function createProject(Project $project): void
{
    $this->entityManager->persist($project);
    $this->entityManager->flush();
    
    // Eventuel envoi de notification
    $this->dispatcher->dispatch(new ProjectCreatedEvent($project));
}

    // Ajoute ici d'autres méthodes métier liées aux projets
}