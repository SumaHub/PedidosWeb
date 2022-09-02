<?php

namespace App\Repository\Main;

use App\Entity\Main\AdOrg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdOrg|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdOrg|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdOrg[]    findAll()
 * @method AdOrg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdOrgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdOrg::class);
    }
}
