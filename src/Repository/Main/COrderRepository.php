<?php

namespace App\Repository\Main;

use App\Entity\Main\COrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method COrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method COrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method COrder[]    findAll()
 * @method COrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class COrderRepository extends ServiceEntityRepository
{
    public $sequence;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, COrder::class);

        /** Table Sequence */
        $RSequence = new AdSequenceRepository($registry);
        $this->sequence = $RSequence->findBy(['name' => $this->getClassMetadata()->getTableName()]);
        $this->sequence = $this->sequence[0];
    }
}
