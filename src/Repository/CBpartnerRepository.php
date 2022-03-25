<?php

namespace App\Repository;

use App\Entity\CBpartner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
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
     * @return array Terceros
     */
    public function findBySalesRep(int $salesrep_id)
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 
            "SELECT cb.C_BPartner_ID
            FROM SM_Sales_Rep ssr
            JOIN C_BPartner cb ON cb.C_BPartner_ID = ssr.C_BPartner_ID
            WHERE cb.IsActive = 'Y' AND cb.IsCustomer = 'Y' AND ssr.SalesRep_ID = :user_id";
        $stmt = $connection->prepare($sql);
        $resulSet = $stmt->executeQuery(['user_id' => $salesrep_id]);

        return $resulSet->fetchFirstColumn();
    }
}
