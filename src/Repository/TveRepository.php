<?php

namespace App\Repository;

use App\Entity\Tve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tve[]    findAll()
 * @method Tve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tve::class);
    }

    // /**
    //  * @return Tve[] Returns an array of Tve objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tve
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    
}
