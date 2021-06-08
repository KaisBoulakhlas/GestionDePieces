<?php

namespace App\Repository;

use App\Entity\EstimateLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EstimateLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstimateLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstimateLine[]    findAll()
 * @method EstimateLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstimateLine::class);
    }

    // /**
    //  * @return EstimateLine[] Returns an array of EstimateLine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EstimateLine
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
