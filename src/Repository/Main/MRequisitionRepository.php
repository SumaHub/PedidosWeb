<?php

namespace App\Repository\Main;

use App\Entity\Main\MRequisition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MRequisition|null find($id, $lockMode = null, $lockVersion = null)
 * @method MRequisition|null findOneBy(array $criteria, array $orderBy = null)
 * @method MRequisition[]    findAll()
 * @method MRequisition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MRequisitionRepository extends ServiceEntityRepository
{
    public $sequence;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MRequisition::class);

        /** Table Sequence */
        $RSequence = new AdSequenceRepository($registry);
        $this->sequence = $RSequence->findBy(['name' => $this->getClassMetadata()->getTableName()]);
        $this->sequence = $this->sequence[0];
    }
}
