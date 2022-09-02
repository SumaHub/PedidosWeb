<?php

namespace App\Repository\Main;

use App\Entity\Main\SmCharacteristic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmCharacteristic|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmCharacteristic|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmCharacteristic[]    findAll()
 * @method SmCharacteristic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmCharacteristicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmCharacteristic::class);
    }
}
