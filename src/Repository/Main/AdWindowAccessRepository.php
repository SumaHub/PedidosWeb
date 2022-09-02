<?php

namespace App\Repository\Main;

use App\Entity\Main\AdWindowAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdWindowAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdWindowAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdWindowAccess[]    findAll()
 * @method AdWindowAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdWindowAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdWindowAccess::class);
    }
}
