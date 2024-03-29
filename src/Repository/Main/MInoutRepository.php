<?php

namespace App\Repository\Main;

use App\Entity\Main\MInout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MInout|null find($id, $lockMode = null, $lockVersion = null)
 * @method MInout|null findOneBy(array $criteria, array $orderBy = null)
 * @method MInout[]    findAll()
 * @method MInout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MInoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MInout::class);
    }
}
