<?php

namespace App\Repository;

use App\Entity\CPaymentterm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CPaymentterm|null find($id, $lockMode = null, $lockVersion = null)
 * @method CPaymentterm|null findOneBy(array $criteria, array $orderBy = null)
 * @method CPaymentterm[]    findAll()
 * @method CPaymentterm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CPaymenttermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CPaymentterm::class);
    }
}
