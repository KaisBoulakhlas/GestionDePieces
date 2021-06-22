<?php

namespace App\Repository;

use App\Entity\RangeRealisation;
use App\Entity\Realisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Realisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Realisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Realisation[]    findAll()
 * @method Realisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Realisation::class);
    }

    /**
     * @param Range $range
     * @return Operation[] Returns an array of Advert objects
     */

    public function findAllOperationsByRange(Range $range)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.ranges', 'r')
            ->where('r.id = :ranges')
            ->setParameter('ranges',$range)
            ->getQuery()
            ->getResult()
            ;

    }

    /**
     * @param RangeRealisation $rangeRealisation
     * @return int|mixed|string
     */

    public function findAllRealisationsByRangeRealisation(RangeRealisation $rangeRealisation)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.rangeRealisation', 'r')
            ->where('r.id = :rangeRealisation')
            ->setParameter('rangeRealisation',$rangeRealisation)
            ->getQuery()
            ->getResult()
            ;

    }

    // /**
    //  * @return Realisation[] Returns an array of Realisation objects
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
    public function findOneBySomeField($value): ?Realisation
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
