<?php

namespace App\Repository;

use App\Entity\OrderPurchaseLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderPurchaseLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderPurchaseLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderPurchaseLine[]    findAll()
 * @method OrderPurchaseLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderPurchaseLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPurchaseLine::class);
    }

    // /**
    //  * @return OrderPurchaseLine[] Returns an array of OrderPurchaseLine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderPurchaseLine
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
