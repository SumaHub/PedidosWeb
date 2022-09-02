<?php

namespace App\Repository\Main;

use App\Entity\Main\SmModeloProducto;
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
}
