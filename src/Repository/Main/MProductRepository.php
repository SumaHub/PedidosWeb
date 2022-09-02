<?php

namespace App\Repository\Main;

use App\Entity\Main\MProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method MProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method MProduct[]    findAll()
 * @method MProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MProduct::class);
    }

    /**
     * Busca los productos mas vendidos
     * por organizacion
     * 
     * @param int $ad_org_id Identificador de la organizacion
     * 
     * @return null|MProduct[] Productos Destacados
     */
    public function findFeaturedProduct(Int $sm_marca_id, Int $limit = 5): Array
    {
        $qb = $this->createQueryBuilder('mp');

        $query = $qb
            ->where(
                $qb->expr()->eq('mp.isactive', "'Y'"),
                $qb->expr()->eq('mp.issold', "'Y'"),
                $qb->expr()->eq('mp.sm_marca_id', ':marca_id')
            )
            ->setParameters(
                new ArrayCollection([
                    new Parameter('marca_id', $sm_marca_id)
                ])
            )
            ->orderBy(
                $qb->expr()->desc('mp.m_product_id')
            )
            ->setMaxResults($limit)
            ->getQuery();
        
        return $query->getResult();
    }

    /**
     * Buscar productos con imagenes para el catalogo
     * 
     * @param int $sm_marca_id Identificador de la marca
     * @param int $m_product_category_id Identificador de la Categoria
     * @return array Productos
     */
    public function findToCatalog(Int $sm_marca_id, Int $m_product_category_id = 0): Array
    {
        $qb = $this->createQueryBuilder('mp');
        $query = $qb
            ->join('mp.m_productdownload', 'mpd')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('mp.isactive', "'Y'"),
                    $qb->expr()->eq('mp.issold', "'Y'"),
                    $qb->expr()->eq('mp.sm_marca_id', ':marca_id'),
                    $qb->expr()->eq('mpd.isactive', "'Y'"),
                    $qb->expr()->like('mpd.downloadurl', ':format')
                )
            );
            
        if ($m_product_category_id > 0)
            $query->andWhere( $qb->expr()->eq('mp.m_product_category_id', ':category_id') );

        $query->setParameters(
            new ArrayCollection([
                new Parameter('marca_id', $sm_marca_id),
                new Parameter('format', '%.png')
            ])
        );
            
        if ($m_product_category_id > 0)
            $query->setParameter('category_id', $m_product_category_id);
        
        $query
            ->orderBy('mp.m_product_category_id', 'DESC')
            ->addOrderBy('mp.m_product_id', 'DESC');
        return $query
            ->getQuery()
            ->getResult();
    }

    /**
     * Busca los productos con sus cantidades 
     * disponibles segun su almacen o ubicacion
     * 
     * @param int $m_product_id Identificador del producto
     * @param int $m_warehouse_id Identificador del almacen
     * @param int $m_locator_id Identificador de la ubicacion
     * 
     * @return float Productos
     */
    public function findStorage(Int $m_product_id, Int $m_warehouse_id, Int $m_locator_id = 0): Float
    {
        $connection = $this->getEntityManager()->getConnection();

        $stmt = $connection->prepare("SELECT bomqtyonhand(:product_id, :warehouse_id, :locator_id) AS QtyToOrder");
        $resulSet = $stmt->executeQuery([
            'product_id' => $m_product_id,
            'warehouse_id' => $m_warehouse_id,
            'locator_id' => $m_locator_id
        ]);

        return $resulSet->fetchOne();
    }
}
