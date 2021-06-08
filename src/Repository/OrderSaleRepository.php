<?php

namespace App\Repository;

use App\Entity\OrderSale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderSale|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderSale|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderSale[]    findAll()
 * @method OrderSale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderSaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderSale::class);
    }

    // /**
    //  * @return OrderSale[] Returns an array of OrderSale objects
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
    public function findOneBySomeField($value): ?OrderSale
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
