<?php

namespace App\Repository;

use App\Entity\AdRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdRole[]    findAll()
 * @method AdRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdRole::class);
    }
}
