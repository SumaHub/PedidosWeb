<?php

namespace App\Repository\Main;

use App\Entity\Main\CBpartnerLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CBpartnerLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CBpartnerLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CBpartnerLocation[]    findAll()
 * @method CBpartnerLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CBpartnerLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CBpartnerLocation::class);
    }
}
