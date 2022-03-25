<?php

namespace App\Repository;

use App\Entity\AdOrginfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdOrginfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdOrginfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdOrginfo[]    findAll()
 * @method AdOrginfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdOrginfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdOrginfo::class);
    }
}
