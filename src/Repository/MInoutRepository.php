<?php

namespace App\Repository;

use App\Entity\MInout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MInout|null find($id, $lockMode = null, $lockVersion = null)
 * @method MInout|null findOneBy(array $criteria, array $orderBy = null)
 * @method MInout[]    findAll()
 * @method MInout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MInoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MInout::class);
    }

    // /**
    //  * @return MInout[] Returns an array of MInout objects
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
    public function findOneBySomeField($value): ?MInout
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
