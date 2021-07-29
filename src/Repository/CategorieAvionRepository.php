<?php

namespace App\Repository;

use App\Entity\CategorieAvion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategorieAvion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieAvion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieAvion[]    findAll()
 * @method CategorieAvion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieAvionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieAvion::class);
    }

    // /**
    //  * @return CategorieAvion[] Returns an array of CategorieAvion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieAvion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
