<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\OffreSearch;
use Doctrine\ORM\Query;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }


    /**
     * @return Offre[] Returns an array of Offre objects
    */

    public function findFeaturedOffre()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.created_at', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Offre[] Returns an array of Offre objects
    */

    public function findSearch(OffreSearch $Search): Query
    {

        $query = $this->createQueryBuilder('o');
        if($Search->getTitre()){
            $query = $query
                ->andWhere('o.titre LIKE :titreSearch')
                ->setParameter('titreSearch', '%'.$Search->getTitre().'%');
        }
        if($Search->getEntreprise()){
            $query = $query
                ->andWhere('o.entreprise LIKE :entrepriseSearch')
                ->setParameter('entrepriseSearch', '%'.$Search->getEntreprise().'%');
        }
        if($Search->getDescription()){
            $query = $query
                ->andWhere('o.description LIKE :descriptionSearch')
                ->setParameter('descriptionSearch', '%'.$Search->getDescription().'%');
        }
        return $query->getQuery();
    }

    // /**
    //  * @return Offre[] Returns an array of Offre objects
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
    public function findOneBySomeField($value): ?Offre
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
