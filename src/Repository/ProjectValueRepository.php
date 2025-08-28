<?php

namespace App\Repository;

use App\Entity\ProjectValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectValue>
 */
class ProjectValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectValue::class);
    }


    public function findTopAttributes(int $limit): array
{
    return $this->createQueryBuilder('pv')
        ->select('a.name, COUNT(pv.id) as count')
        ->join('pv.attribute', 'a')
        ->groupBy('a.name')
        ->orderBy('count', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return ProjectValue[] Returns an array of ProjectValue objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProjectValue
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
