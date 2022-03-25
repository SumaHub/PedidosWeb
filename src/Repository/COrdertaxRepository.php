<?php

namespace App\Repository;

use App\Entity\COrdertax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method COrdertax|null find($id, $lockMode = null, $lockVersion = null)
 * @method COrdertax|null findOneBy(array $criteria, array $orderBy = null)
 * @method COrdertax[]    findAll()
 * @method COrdertax[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class COrdertaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, COrdertax::class);
    }
}
