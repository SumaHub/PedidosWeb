<?php

namespace App\Repository;

use App\Entity\MProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return array Productos Destacados
     */
    public function findFeaturedProduct(Int $ad_org_id, Int $limit = 5)
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = 
            "SELECT mpc.Name AS category, mp.Name, SUM(col.Qtyordered) as qty
            FROM C_Orderline col
            JOIN C_Order co ON co.C_Order_ID = col.C_Order_ID 
            JOIN M_Product mp ON mp.M_Product_ID = col.M_Product_ID 
            JOIN M_Product_Category mpc ON mpc.M_Product_Category_ID = mp.M_Product_Category_ID 
            WHERE co.Issotrx = 'Y' AND co.Isactive = 'Y' AND co.Docstatus IN ('CO', 'CL') AND co.AD_Org_ID = :org_id
            GROUP BY mpc.M_Product_Category_ID, mp.M_Product_ID 
            ORDER BY SUM(col.Qtyordered) DESC
            LIMIT :limit_p";
        $stmt = $connection->prepare($sql);
        $resulSet = $stmt->executeQuery(['org_id' => $ad_org_id, 'limit_p' => $limit]);

        return $resulSet->fetchAllAssociative();
    }

    /**
     * Busca los productos con sus cantidades 
     * disponibles segun su almacen o ubicacion
     * 
     * @param int $m_product_id Identificador del producto
     * @param int $m_warehouse_id Identificador del almacen
     * @param int $m_locator_id Identificador de la ubicacion
     * 
     * @return array Productos
     */
    public function findStorage(Int $m_product_id, Int $m_warehouse_id, Int $m_locator_id = 0)
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
