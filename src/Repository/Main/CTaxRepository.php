<?php

namespace App\Repository\Main;

use App\Entity\Main\CTax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CTax|null find($id, $lockMode = null, $lockVersion = null)
 * @method CTax|null findOneBy(array $criteria, array $orderBy = null)
 * @method CTax[]    findAll()
 * @method CTax[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CTaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CTax::class);
    }
}
