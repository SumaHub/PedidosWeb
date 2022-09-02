<?php

namespace App\Repository\Main;

use App\Entity\Main\AdProcessAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdProcessAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdProcessAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdProcessAccess[]    findAll()
 * @method AdProcessAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdProcessAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdProcessAccess::class);
    }
}
