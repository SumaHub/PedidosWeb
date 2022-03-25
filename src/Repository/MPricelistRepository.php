<?php

namespace App\Repository;

use App\Entity\MPricelist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MPricelist|null find($id, $lockMode = null, $lockVersion = null)
 * @method MPricelist|null findOneBy(array $criteria, array $orderBy = null)
 * @method MPricelist[]    findAll()
 * @method MPricelist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MPricelistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MPricelist::class);
    }
}
