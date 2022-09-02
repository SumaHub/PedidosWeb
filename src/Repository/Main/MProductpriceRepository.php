<?php

namespace App\Repository\Main;

use App\Entity\Main\MProductprice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MProductprice|null find($id, $lockMode = null, $lockVersion = null)
 * @method MProductprice|null findOneBy(array $criteria, array $orderBy = null)
 * @method MProductprice[]    findAll()
 * @method MProductprice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MProductpriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MProductprice::class);
    }

    /**
     * Busca los precios de los productos
     * 
     * @param int $ad_org_id Identificador de la organizacion
     * @param int $m_pricelist_id Identificador de la lista de precios
     * @param string $value Codigo o Nombre del producto
     * 
     * @return null|MProductprice[] Productos con precios
     */
    public function findProductPrices(Int $ad_org_id, Int $m_pricelist_id, String $value = '')
    {
        $qb = $this->createQueryBuilder('mpp');

        $query = $qb
            ->join('mpp.m_pricelist_version', 'mpv')
            ->join('mpv.sm_precio_estimadolines', 'spel')
            ->join('mpp.m_product', 'mp')
            ->leftjoin('mp.m_productdownload', 'mpd', 'with', "mpd.iscover = 'Y'")
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('mpp.ad_org_id', ':org_id'),
                    $qb->expr()->eq('mpv.m_pricelist_id', ':pricelist_id'),
                    $qb->expr()->eq('mpv.isactive', "'Y'"),
                    $qb->expr()->eq('mp.issummary', "'N'"),
                    $qb->expr()->eq('mp.isactive', "'Y'"),
                    $qb->expr()->eq('mp.issold', "'Y'"),
                    $qb->expr()->like('mp.value', ':value')
                )
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter('org_id', $ad_org_id),
                    new Parameter('pricelist_id', $m_pricelist_id),
                    new Parameter('value', "%". $value ."%")
                ])
            )
            ->getQuery();

        return $query->getResult();
    }
}
