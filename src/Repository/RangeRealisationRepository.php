<?php

namespace App\Repository;

use App\Entity\RangeRealisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RangeRealisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method RangeRealisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method RangeRealisation[]    findAll()
 * @method RangeRealisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RangeRealisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RangeRealisation::class);
    }

    // /**
    //  * @return RangeRealisation[] Returns an array of RangeRealisation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RangeRealisation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
