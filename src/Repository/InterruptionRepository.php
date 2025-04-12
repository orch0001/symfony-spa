<?php

namespace App\Repository;

use App\Entity\Interruption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Interruption>
 */
class InterruptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Interruption::class);
    }

    public function findLastFiveInterruption()
    {
        return $this->createQueryBuilder('e')
            ->where('e.endDate > e.start') 
            ->orderBy('e.endDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function deleteAllInterruptions()
    {
        return $this->createQueryBuilder('e')
        ->delete()
        ->getQuery()
        ->execute();
    }



    //    /**
    //     * @return Interruption[] Returns an array of Interruption objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Interruption
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
