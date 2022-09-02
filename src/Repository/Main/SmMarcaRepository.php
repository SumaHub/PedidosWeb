<?php

namespace App\Repository\Main;

use App\Entity\Main\SmMarca;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmMarca|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmMarca|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmMarca[]    findAll()
 * @method SmMarca[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmMarcaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmMarca::class);
    }
}
