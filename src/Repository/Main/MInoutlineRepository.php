<?php

namespace App\Repository\Main;

use App\Entity\Main\MInoutline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MInoutline|null find($id, $lockMode = null, $lockVersion = null)
 * @method MInoutline|null findOneBy(array $criteria, array $orderBy = null)
 * @method MInoutline[]    findAll()
 * @method MInoutline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MInoutlineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MInoutline::class);
    }
}
