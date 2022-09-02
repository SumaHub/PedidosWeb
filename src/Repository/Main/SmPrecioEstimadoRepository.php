<?php

namespace App\Repository\Main;

use App\Entity\Main\SmPrecioEstimado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SmPrecioEstimado|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmPrecioEstimado|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmPrecioEstimado[]    findAll()
 * @method SmPrecioEstimado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmPrecioEstimadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SmPrecioEstimado::class);
    }

    /**
     * Buscar el ultimo precio de lista estimado
     * 
     * @param int $m_pricelist_id
     * @param int $m_product_id Identificador del producto
     * 
     * @return null|SmPrecioEstimado
     */
    public function findLastEstimacionByPricelist(Int $m_pricelist_id, Int $m_product_id, String $status = 'Y'): SmPrecioEstimado
    {
        $qb = $this->createQueryBuilder('spe');

        $query = $qb
            ->join('spe.sm_precio_estimadolines', 'spel')
            ->join('spel.m_pricelist_version', 'mpv')
            ->join('mpv.m_pricelist', 'mpl')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('spe.docstatus', ':status'),
                    $qb->expr()->eq('spel.listo', "'Y'"),
                    $qb->expr()->eq('mpl.m_pricelist_id', ':pricelist_id'),
                    $qb->expr()->eq('spel.m_product_id', ':product_id')
                )
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter(':status', $status),
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
