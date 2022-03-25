<?php

namespace App\Repository;

use App\Entity\MProductdownload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MProductdownload|null find($id, $lockMode = null, $lockVersion = null)
 * @method MProductdownload|null findOneBy(array $criteria, array $orderBy = null)
 * @method MProductdownload[]    findAll()
 * @method MProductdownload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MProductdownloadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MProductdownload::class);
    }
}
