<?php

namespace App\Repository;

use App\Entity\MPricelistVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MPricelistVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method MPricelistVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method MPricelistVersion[]    findAll()
 * @method MPricelistVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MPricelistVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MPricelistVersion::class);
    }
}
