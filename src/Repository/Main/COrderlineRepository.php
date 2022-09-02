<?php

namespace App\Repository\Main;

use App\Entity\Main\COrderline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method COrderline|null find($id, $lockMode = null, $lockVersion = null)
 * @method COrderline|null findOneBy(array $criteria, array $orderBy = null)
 * @method COrderline[]    findAll()
 * @method COrderline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class COrderlineRepository extends ServiceEntityRepository
{
    public $sequence;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, COrderline::class);

        /** Table Sequence */
        $RSequence = new AdSequenceRepository($registry);
        $this->sequence = $RSequence->findBy(['name' => $this->getClassMetadata()->getTableName()]);
        $this->sequence = $this->sequence[0];
    }
}
