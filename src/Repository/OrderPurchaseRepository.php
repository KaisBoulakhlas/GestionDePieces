<?php

namespace App\Repository;

use App\Entity\OrderPurchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderPurchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderPurchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderPurchase[]    findAll()
 * @method OrderPurchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderPurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPurchase::class);
    }

    public function findAllOrderPurchaseByMonth(int $month){
        return $this->createQueryBuilder('o')
            ->andWhere('EXTRACT(MONTH FROM o.dateDeliveryPredicted) = :month')
            ->setParameter('month', $month)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return OrderPurchase[] Returns an array of OrderPurchase objects
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
    public function findOneBySomeField($value): ?OrderPurchase
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
