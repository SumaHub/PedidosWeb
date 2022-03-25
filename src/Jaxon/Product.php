<?php

namespace App\Jaxon;

use App\Entity\MProductprice;
use App\Model\Product as ModelProduct;
use App\Repository\MProductpriceRepository;
use App\Repository\MProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Product extends Base
{
    public $data;

    public static function imagesPerProduct(
        String $codigo
    )
    {
        $product = new ModelProduct;
        return $product->get_images($codigo);
    }
    
    public static function getProduct(
        String $codigo
    )
    {
        $product = new ModelProduct;
        return $product->get_product($codigo);
    }

    /**
     * Obtiene los productos que no poseen un
     * precio para la venta en el sistema
     * 
     * @param int $M_Product_ID Identificador del producto
     * 
     * @return string|ADORecordSet Mensaje de error | Productos
     */
    public static function getWithoutPrice(
        Int $M_Product_ID = 0
    )
    {
        $product = new ModelProduct;
        return $product->get_without_price($M_Product_ID);
    }
    
    public static function showProducts()
    {
        $product = new ModelProduct;
        return $product->get_products('', 0, 0, null, 10000);
    } 
}

?>