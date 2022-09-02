<?php

namespace App\Repository\Main;

use App\Entity\Main\SmCategoriaProductoMarca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmCategoriaProductoMarca|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmCategoriaProductoMarca|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmCategoriaProductoMarca[]    findAll()
 * @method SmCategoriaProductoMarca[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmCategoriaProductoMarcaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmCategoriaProductoMarca::class);
    }

    // /**
    //  * @return SmCategoriaProductoMarca[] Returns an array of SmCategoriaProductoMarca objects
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
    public function findOneBySomeField($value): ?SmCategoriaProductoMarca
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
