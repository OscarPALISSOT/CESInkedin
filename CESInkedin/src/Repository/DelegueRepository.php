<?php

namespace App\Repository;

use App\Entity\Delegue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Delegue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delegue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delegue[]    findAll()
 * @method Delegue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelegueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delegue::class);
    }

    // /**
    //  * @return Delegue[] Returns an array of Delegue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Delegue
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
