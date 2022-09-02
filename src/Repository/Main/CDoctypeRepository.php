<?php

namespace App\Repository\Main;

use App\Entity\Main\CDoctype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CDoctype|null find($id, $lockMode = null, $lockVersion = null)
 * @method CDoctype|null findOneBy(array $criteria, array $orderBy = null)
 * @method CDoctype[]    findAll()
 * @method CDoctype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CDoctypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CDoctype::class);
    }
}
