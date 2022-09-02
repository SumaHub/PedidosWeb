<?php

namespace App\Repository\Main;

use App\Entity\Main\SmSalesRep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmSalesRep|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmSalesRep|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmSalesRep[]    findAll()
 * @method SmSalesRep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmSalesRepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmSalesRep::class);
    }
}
