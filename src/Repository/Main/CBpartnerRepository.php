<?php

namespace App\Repository\Main;

use App\Entity\Main\CBpartner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CBpartner|null find($id, $lockMode = null, $lockVersion = null)
 * @method CBpartner|null findOneBy(array $criteria, array $orderBy = null)
 * @method CBpartner[]    findAll()
 * @method CBpartner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CBpartnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CBpartner::class);
    }

    /**
     * Busca los terceros asignados a un 
     * Representante Comercial
     * 
     * @param int $salesrep_id Identificador del Vendedor
     * 
     * @return null|CBpartner[] Clientes
     */
    public function findBySalesRep(int $salesrep_id)
    {
        $qb = $this->createQueryBuilder('cb');

        $query = $qb
            ->join('cb.smSalesReps', 'ssr')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('cb.isactive', "'Y'"),
                    $qb->expr()->eq('cb.iscustomer', "'Y'"),
                    $qb->expr()->eq('ssr.salesrep_id', ':salesrep_id')
                )
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter('salesrep_id', $salesrep_id)
                ])
            )
            ->getQuery();

        return $query->getResult();
    }
}
