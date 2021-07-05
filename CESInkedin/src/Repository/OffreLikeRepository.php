<?php

namespace App\Repository;

use App\Entity\OffreLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OffreLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreLike[]    findAll()
 * @method OffreLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OffreLike::class);
    }

    // /**
    //  * @return OffreLike[] Returns an array of OffreLike objects
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
    public function findOneBySomeField($value): ?OffreLike
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
