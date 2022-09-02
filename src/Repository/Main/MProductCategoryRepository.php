<?php

namespace App\Repository\Main;

use App\Entity\Main\MProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MProductCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MProductCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MProductCategory[]    findAll()
 * @method MProductCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MProductCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MProductCategory::class);
    }
}
