<?php

namespace App\Repository;

use App\Entity\MInoutline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MInoutline|null find($id, $lockMode = null, $lockVersion = null)
 * @method MInoutline|null findOneBy(array $criteria, array $orderBy = null)
 * @method MInoutline[]    findAll()
 * @method MInoutline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MInoutlineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MInoutline::class);
    }

    // /**
    //  * @return MInoutline[] Returns an array of MInoutline objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MInoutline
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
