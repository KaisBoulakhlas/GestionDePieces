<?php

namespace App\Repository;

use App\Entity\PieceUsed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PieceUsed|null find($id, $lockMode = null, $lockVersion = null)
 * @method PieceUsed|null findOneBy(array $criteria, array $orderBy = null)
 * @method PieceUsed[]    findAll()
 * @method PieceUsed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PieceUsedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PieceUsed::class);
    }

    // /**
    //  * @return PieceUsed[] Returns an array of PieceUsed objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PieceUsed
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
