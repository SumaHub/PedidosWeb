<?php

namespace App\Jaxon;

use App\Model\Product as ModelProduct;
use Jaxon\Response\Response;

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

    public static function getProducts(
        String $code = '', 
        Array $order
    )
    {
        $jxnr   = new Response;

        // La organizacion debe ser seleccionada para listar los productos
        if(!isset($order['organizacion']) || $order['organizacion'] == 0)
            return $jxnr->script('Swal.fire("Oops!", "Primero debe seleccionar una organizaci&oacute;n!", "warning")');

        // La lista de precio debe ser seleccionada para mostrar los precios de los articulos
        if(!isset($order['precio']) || $order['precio'] == 0)
            return $jxnr->script('Swal.fire("Oops!", "No has seleccionado una lista de precio!", "warning")');

        $products   = ModelProduct::get_products($code, $order['organizacion'], $order['precio'], $_SESSION['user']['ad_user_id'], 10000);
        $html       = '<table id="tablaProducto" class="table"><thead><tr><th>Marca</th><th>Nombre</th><th>C&oacute;digo</th><th>Cantidad</th><th>Precio</th></tr></thead><tbody>';
        if($products->numRows() > 0) {
            while ($product = $products->fetchRow()) {
                $html .= 
                '<tr style="font-size: 0.9rem; cursor: pointer;" onclick="App.Jaxon.OrderLine.createTemp(\''. $product['value'] .'\', jaxon.getFormValues(\'pedidoDatos\'))">
                    <td>'.$product['brand'].'</td>
                    <td>'.$product['product'].'</td>
                    <td>'.$product['value'].'</td>
                    <td>';
                $html .= ($product['qty'] > 0) ? '<span class="py-1 px-2 badge badge-success"><i class="fas fa-box mr-2"></i>'. $product['qty'] .' UNI</span>' : '<span class="py-1 px-2 badge badge-warning"><i class="fas fa-box mr-2"></i>'. $product['qty'] .' UNI</span>';
                $html .=
                    '</td>
                    <td>$'.$product['pricelist'].'</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">NO SE HA ENCONTRADO NINGUN PRODUCTO</td></tr>';
        }
        $html       .= '</tbody></table>';
        $jxnr
            ->assign('productos', 'innerHTML', $html)
            ->script('$("#tablaProducto").dataTable({ columnDefs: [{ orderable: true, targets: 0 }], order: [[1, "asc" ]], responsive: true, lengthChange: false, autoWidth: false, dom: \'<"row"<"col-sm-12 col-md-12"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>\', buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"] })');
        return $jxnr;  
    } 

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