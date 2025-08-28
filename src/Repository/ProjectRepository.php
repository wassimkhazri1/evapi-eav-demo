<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

// App\Repository\ProjectRepository.php
public function findByUserPaginated(User $user, PaginatorInterface $paginator, int $page = 1, int $itemsPerPage = 10)
{
    $query = $this->createQueryBuilder('p')
        ->andWhere('p.user = :user')
        ->setParameter('user', $user)
        ->orderBy('p.name', 'ASC')
        ->getQuery();

    return $paginator->paginate(
        $query,
        $page,
        $itemsPerPage
    );
}
}
