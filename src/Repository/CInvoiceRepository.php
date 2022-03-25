<?php

namespace App\Repository;

use App\Entity\CInvoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CInvoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method CInvoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method CInvoice[]    findAll()
 * @method CInvoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CInvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CInvoice::class);
    }

    // /**
    //  * @return CInvoice[] Returns an array of CInvoice objects
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
    public function findOneBySomeField($value): ?CInvoice
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
