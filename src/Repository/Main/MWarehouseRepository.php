<?php

namespace App\Repository\Main;

use App\Entity\Main\MWarehouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MWarehouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method MWarehouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method MWarehouse[]    findAll()
 * @method MWarehouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MWarehouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MWarehouse::class);
    }
}
