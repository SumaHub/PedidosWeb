<?php

namespace App\Repository\Main;

use App\Entity\Main\MRequisitionline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MRequisitionline|null find($id, $lockMode = null, $lockVersion = null)
 * @method MRequisitionline|null findOneBy(array $criteria, array $orderBy = null)
 * @method MRequisitionline[]    findAll()
 * @method MRequisitionline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MRequisitionlineRepository extends ServiceEntityRepository
{
    public $sequence;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MRequisitionline::class);

        /** Table Sequence */
        $RSequence = new AdSequenceRepository($registry);
        $this->sequence = $RSequence->findBy(['name' => $this->getClassMetadata()->getTableName()]);
        $this->sequence = $this->sequence[0];
    }
}
