<?php

namespace App\Repository\Main;

use App\Entity\Main\AdRoleIncluded;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdRoleIncluded|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdRoleIncluded|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdRoleIncluded[]    findAll()
 * @method AdRoleIncluded[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRoleIncludedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdRoleIncluded::class);
    }
}
