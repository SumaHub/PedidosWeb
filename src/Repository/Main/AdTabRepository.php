<?php

namespace App\Repository\Main;

use App\Entity\Main\AdTab;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdTab|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdTab|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdTab[]    findAll()
 * @method AdTab[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdTabRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdTab::class);
    }
}
