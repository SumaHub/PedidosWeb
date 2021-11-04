<?php

namespace App\Jaxon;

use App\Model\OrderLine as ModelOrderLine;
use App\Model\Product as ModelProduct;
use App\Util;
use Jaxon\Response\Response;

class OrderLine extends Base
{
    /**
     * Habilitar campo para cambiar cantidades
     * de una linea en la orden
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function changeQty(
        Int $SM_Orderline_ID
    )
    {
        $jxnr       = new Response;
        $orderline  = new ModelOrderLine; 
        $input      = 
            '<input 
                type="number"
                step="0.01"
                value="'. number_format($orderline->get_qty($SM_Orderline_ID), 2) .'"
                onblur="App.Jaxon.OrderLine.setQty(this.parentElement.parentElement.dataset.line, this.value)"
            >';
        return $jxnr->assign(base64_encode($SM_Orderline_ID . 'qty'), 'innerHTML', $input);
    }

    /**
     * Crear lineas de las ordenes temporales
     * 
     * @param array $code Codigo del producto
     * @param array $order Datos de la orden
     * 
     * @return string/array Mensaje de error / Datos de la linea
     */
    public static function createTemp(
        String $code,
        Array $order
    )
    {
        $jxnr           = new Response;
        $modelOrderline = new ModelOrderLine;
        $modelProduct   = new ModelProduct;

        // Verificar la sesion del usuario
        if(Util::VerifySession()) return $jxnr->redirect('/ingresar');

        // Crear la orden temporal si no existe
        if( !isset( $_SESSION['order'] ) )
            $order = Order::createTemp($order);
        else 
            $order = $_SESSION['order'];

        // Verificar los datos de la orden temporal
        if( !isset( $order['m_pricelist_id']) || !isset( $order['sm_order_id'] ) )
            return $jxnr->script( self::displayError("Oops!", "Ha fallado el guardado automatico de la Orden", var_dump($order) ) );

        // Obtener datos de producto
        $product    = $modelProduct->get_product($code, $order['m_pricelist_id']);

        // Verifica si el producto tiene stock
        if( $modelProduct->get_storage($order["ad_org_id"], $order['m_warehouse_id'], $product["m_product_id"]) == 0) 
            return $jxnr->script( self::displayError("Producto sin Stock", "Este producto no cuenta con cantidades disponibles.") );

        // Verifica si el producto ya esta registrado en la orden
        if( $modelOrderline->product_exist($order['sm_order_id'], $product["m_product_id"]) )
            return $jxnr->script( self::displayError("Producto Repetido", "Este producto se encuentra registrado, modifique la cantidad de ser necesario.") );

        // Crear linea de la orden
        $orderline  = $modelOrderline->create_temp($order, $product);

        if( !isset( $orderline['sm_orderline_id'] ) )
            return $jxnr->script( self::displayError("Oops!", "Ha fallado el guardado automatico de la Linea", $orderline) );

        // Actualizar monto de la orden
        if( Order::syncAmt($order['sm_order_id']) )
            $order = array_replace_recursive($order, $_SESSION['order']);

        // Crear respuesta HTML
        $html = 
        '<tr id="'. base64_encode($orderline['sm_orderline_id']) .'" class="text-center orderline" data-line="'. $orderline['sm_orderline_id'] .'">
            <td class="text-center">'. $orderline['line'] .'</td>
            <td class="text-left">'. $product['nombre'] .'</td>
            <td class="text-center">'. $product['codigo'] .'</td>
            <td class="text-right">'. number_format($product['pricelist'], 2) .'</td>
            <td 
                id="'. base64_encode($orderline['sm_orderline_id'] . "qty") .'" 
                class="text-right qty"
            >
                <p onclick="App.Jaxon.OrderLine.changeQty(this.parentElement.parentElement.dataset.line)">'. number_format($orderline['qtyordered'], 2) .'</p>
            </td>
            <td 
                id="'. base64_encode($orderline['sm_orderline_id'] . "amt") .'"
                class="text-right"
            >
                '. number_format($orderline['linenetamt'], 2) .'
            </td>
            <td class="btn-action">
                <i onclick="App.Jaxon.Base.actionConfirm(\'deleteOrderline\', \'OrderLine\', this.parentElement.parentElement.dataset.line)" class="fas fa-trash text-danger"></i>
            </td>
        </tr>';
        $jxnr
            ->prepend('tablaProductos', 'innerHTML', $html)
            ->assign('totallines', 'value', number_format($order['totallines'], 2))
            ->assign('grandtotal', 'value', number_format($order['grandtotal'], 2))
            ->assign('taxamt', 'value', number_format($order['grandtotal'] - $order['totallines'], 2));

        return  $jxnr;
    }

    /**
     * Borra una linea de una orden temporal
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function delete(
        Int $SM_Orderline_ID
    )
    {
        $jxnr       = new Response;
        $orderline  = new ModelOrderLine;
        $result     = $orderline->delete($SM_Orderline_ID);

        if ( is_object($result) ) {
            // Actualizar montos de la Orden
            Order::syncAmt($_SESSION['order']['sm_order_id']);

            // Actualizar numeros lineas
            Order::syncLines($_SESSION['order']['sm_order_id']);

            // Actualizar variable de las lineas
            $_SESSION['order']['orderlines'] = self::get($_SESSION['order']['sm_order_id']);

            // Quitar renglon
            $jxnr
                ->remove(base64_encode($SM_Orderline_ID))
                ->assign('totallines', 'value', number_format($_SESSION['order']['totallines'], 2))
                ->assign('taxamt', 'value', number_format($_SESSION['order']['grandtotal'] - $_SESSION['order']['totallines'], 2))
                ->assign('grandtotal', 'value', number_format($_SESSION['order']['grandtotal'], 2));

            foreach ($_SESSION['order']['orderlines'] as $orderline) {
                $jxnr->assign(base64_encode($orderline['sm_orderline_id'] . 'line'), 'innerHTML', $orderline['line']);
            }    
        } else {
            $jxnr->script( self::displayError('Oops!', 'No fue posible borrar la linea de esta orden', json_encode( $result )) );
        }

        return $jxnr;
    }

    public static function get(
        Int $SM_Order_ID,
        Bool $IsTemp = true
    )
    {
        $orderline = new ModelOrderLine;
        return $orderline->get($SM_Order_ID, $IsTemp);
    }

    /**
     * Cambiar cantidad de un producto en una linea 
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * @param string $qty Cantidad
     * 
     * @return object Jaxon\Response\Response
     */
    public function setQty(
        Int $SM_Orderline_ID,
        String $qty
    )
    {
        $jxnr       = new Response;
        $orderline  = new ModelOrderLine; 
        
        $data = $orderline->set_qty($SM_Orderline_ID, $qty);
        if( is_array($data) ) {
            // Actualizar montos
            Order::syncAmt($_SESSION['order']['sm_order_id']);

            // Actualizar campos
            $jxnr
                ->assign(base64_encode($SM_Orderline_ID . 'qty'), 'innerHTML', '<p onclick="App.Jaxon.OrderLine.changeQty(this.parentElement.parentElement.dataset.line)">'. number_format($qty, 2) .'</p>')
                ->assign(base64_encode($SM_Orderline_ID . 'amt'), 'innerHTML', number_format($data['linenetamt'], 2))
                ->assign('totallines', 'value', number_format($_SESSION['order']['totallines'], 2))
                ->assign('taxamt', 'value', number_format($_SESSION['order']['grandtotal'] - $_SESSION['order']['totallines'], 2))
                ->assign('grandtotal', 'value', number_format($_SESSION['order']['grandtotal'], 2));
        } else {
            $jxnr->script(self::displayError("Oops!", "Ha fallado el guardado automatico de la Linea", $data) );
        }

        return $jxnr;
    }
}

?>