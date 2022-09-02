<?php

namespace App\Repository\Main;

use App\Entity\Main\SmPrecioEstimadoline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmPrecioEstimadoline|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmPrecioEstimadoline|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmPrecioEstimadoline[]    findAll()
 * @method SmPrecioEstimadoline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmPrecioEstimadolineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmPrecioEstimadoline::class);
    }

    /**
     * Buscar el ultimo precio estimado
     * 
     * @param int $m_product_id Identificador del producto
     * 
     * @return mixed|SmPrecioEstimadoline 
     */
    public function findLastEstimacion(Int $m_product_id, String $status = 'Y', $promotion = 'N')
    {
        $qb = $this->createQueryBuilder('spel');

        $query = $qb
            ->join('spel.sm_precio_estimado', 'spe')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('spe.docstatus', ':status'), // Estatus
                    $qb->expr()->eq('spe.isactive2', ':promotion'), // Promocion
                    $qb->expr()->eq('spel.m_product_id', ':product_id')
                )
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter(':status', $status),
                    new Parameter(':promotion', $promotion),
                    new Parameter(':product_id', $m_product_id)
                ])
            )
            ->orderBy('spe.updated', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Buscar el ultimo precio de lista estimado
     * 
     * @param int $m_pricelist_id
     * @param int $m_product_id Identificador del producto
     * 
     * @return null|SmPrecioEstimadoline 
     */
    public function findLastEstimacionByPricelist(Int $m_pricelist_id, Int $m_product_id, String $docstatus = 'Y'): SmPrecioEstimadoline
    {
        $qb = $this->createQueryBuilder('spel');

        $query = $qb
            ->join('spel.sm_precio_estimado', 'spe')
            ->join('spel.m_pricelist_version', 'mpv')
            ->join('mpv.m_pricelist', 'mpl')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('spe.docstatus', ':docstatus'),
                    $qb->expr()->eq('spel.listo', "'Y'"),
                    $qb->expr()->eq('mpl.m_pricelist_id', ':pricelist_id'),
                    $qb->expr()->eq('spel.m_product_id', ':product_id')
                )
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter(':docstatus', $docstatus),
                    new Parameter(':pricelist_id', $m_pricelist_id),
                    new Parameter(':product_id', $m_product_id)
                ])
            )
            ->orderBy('spe.updated', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getSingleResult();
    }
}
