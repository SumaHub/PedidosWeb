<?php

namespace App\Repository\Main;

use App\Entity\Main\AdClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdClient[]    findAll()
 * @method AdClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdClient::class);
    }
}
