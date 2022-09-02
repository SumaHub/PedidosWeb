<?php

namespace App\Repository\Main;

use App\Entity\Main\MPricelist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MPricelist|null find($id, $lockMode = null, $lockVersion = null)
 * @method MPricelist|null findOneBy(array $criteria, array $orderBy = null)
 * @method MPricelist[]    findAll()
 * @method MPricelist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MPricelistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MPricelist::class);
    }

    /**
     * Buscar listas de precios activas para un producto
     * 
     * @param int $ad_org_id Identificador 
     */
    public function findByProduct(Int $ad_org_id = 0, Int $m_product_id = 0)
    {
        
    }

    /**
     * Buscar la ultima lista de precio de un producto
     * 
     * @param int $m_product_id Identificador del Producto
     * 
     * @return null|MPricelist
     */
    public function findLastPriceslistByProduct(Int $m_product_id): MPricelist
    {
        $qb = $this->createQueryBuilder('mpl');

        $query = $qb
            ->join('mpl.m_pricelist_versions', 'mpv')
            ->join('mpv.m_productprices', 'mpp')
            ->join('mpp.m_product', 'mp')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('mpl.isactive', "'Y'"),
                    $qb->expr()->eq('mpl.issopricelist', "'Y'"),
                    $qb->expr()->eq('mpv.isactive', "'Y'"),
                    $qb->expr()->eq('mp.m_product_id', ':product_id')
                )
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter(':product_id', $m_product_id)
                ])
            )
            ->orderBy('mpv.updated', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getSingleResult();
    }
}
