<?php

namespace App\Repository;

use App\Entity\SmModeloProducto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmModeloProducto|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmModeloProducto|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmModeloProducto[]    findAll()
 * @method SmModeloProducto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmModeloProductoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmModeloProducto::class);
    }

    // /**
    //  * @return SmModeloProducto[] Returns an array of SmModeloProducto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SmModeloProducto
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
