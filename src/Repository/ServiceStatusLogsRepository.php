<?php

namespace App\Repository;

use App\Entity\ServiceStatusLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceStatusLogs>
 */
class ServiceStatusLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceStatusLogs::class);
    }

    public function findLastSixServiceDesc()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.checkedAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function getAvailabilityRateLast24Hours(int $serviceId): float 

    { 
        $now = new \DateTimeImmutable(); 
        $since = $now->sub(new \DateInterval('P1D')); // P1D = 1 day 

        $qb = $this->createQueryBuilder('e') 
            ->select('COUNT(e.id) as totalChecks') 
            ->addSelect( 
                'SUM(CASE WHEN e.status = :status THEN 1 ELSE 0 END) as functionalCount' 
            ) 
            ->where('e.service = :serviceId') 
            ->andWhere('e.checkedAt >= :since') 
            ->setParameter('serviceId', $serviceId) 
            ->setParameter('since', $since) 
            ->setParameter('status', 'OK'); 

        $result = $qb->getQuery()->getSingleResult(); 

        if ($result['totalChecks'] == 0) { 
            return 0.0; 
        } 

        return ($result['functionalCount'] / $result['totalChecks']) * 100; 

    } 

    public function getAvailabilityRateLast7Days(int $serviceId): float 

    { 
        $now = new \DateTimeImmutable(); 
        $since = $now->sub(new \DateInterval('P7D')); // P7D = 7 days 

        $qb = $this->createQueryBuilder('e') 
            ->select('COUNT(e.id) as totalChecks') 
            ->addSelect( 
                'SUM(CASE WHEN e.status = :status THEN 1 ELSE 0 END) as functionalCount' 
            ) 
            ->where('e.service = :serviceId') 
            ->andWhere('e.checkedAt >= :since') 
            ->setParameter('serviceId', $serviceId) 
            ->setParameter('since', $since) 
            ->setParameter('status', 'OK'); 

        $result = $qb->getQuery()->getSingleResult(); 

        if ($result['totalChecks'] == 0) { 
            return 0.0; 
        } 

        return ($result['functionalCount'] / $result['totalChecks']) * 100; 

    } 
        //    /**
    //     * @return ServiceStatusLogs[] Returns an array of ServiceStatusLogs objects
    //     */
    //    public function findByService($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ServiceStatusLogs
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
