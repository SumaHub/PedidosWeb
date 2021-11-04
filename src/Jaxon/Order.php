<?php

namespace App\Jaxon;

use App\Model\Order as ModelOrder;
use App\Model\OrderLine as ModelOrderLine;
use App\Model\Product as ModelProduct;
use App\Util;
use Jaxon\Response\Response;

class Order extends Base
{
    public function create(
        Int $Order_ID
    )
    {
        $jxnr       = new Response;
        $order      = new ModelOrder;
        $orderline  = new ModelOrderLine;

        // Crear pedido
        $order  = $order->create($Order_ID);

        // Crear lineas
        if( isset($order['c_order_id']) ) {
            $orderlines = $orderline->get($Order_ID);
            foreach ($orderlines as $line) {
                $res = $orderline->create($line['sm_orderline_id'], $order['c_order_id']);
                $jxnr->script('console.log("'. $res .'")');
            }
        } else { 
            $jxnr->script( self::displayError('Oops!', 'No fue posible procesar el pedido.') );
        }

        return $jxnr;
    }

    /**
     * Crear orden temporal en la BD
     * 
     * @param array $order Datos de la orden
     * 
     * @return array Datos de la orden creada temporalmente
     */
    public static function createTemp(
        Array $order
    )
    {
        $model = new ModelOrder;
        return $_SESSION['order'] = $model->create_temp($order) ;
    }

    /**
     * Elimina el pedido de las ordenes activas
     * 
     * @param int $Order_ID Identificador de la orden
     * @param bool $IsTemp Bandera de pedidos temporales
     * 
     * @return string/bool Mensaje de error / Estado de la consulta
     */
    public function delete(
        Int $Order_ID,
        Bool $IsTemp = true
    )
    {
        $jxnr   = new Response;
        $order  = new ModelOrder;

        if( $order->delete($Order_ID, $IsTemp) )
            $jxnr->redirect('/dashboard');
        else 
            $jxnr->script( self::displayError('Oops!', 'No fue posible eliminar el pedido.') );

        return $jxnr;
    }

    /**
     * Devuelve un arreglo con los datos
     * de la orden
     * 
     * @param int $Order_ID Identificador de la orden
     * 
     * @return array Datos de la Orden
     */
    public static function getOrder(
        Int $Order_ID,
        Bool $IsTemp = true
    )
    {
        $model = new ModelOrder;
        return $_SESSION['order'] = $model->get_order($Order_ID, $IsTemp);
    }

    /**
     * Devuelve un arreglo con los datos
     * de las ordenes
     * 
     * @param int $SalesRep_ID Identificador del vendedor
     * @param bool $IsTemp Bandera de pedidos temporales
     * @param int $limit Limite de registros devueltos
     * 
     * @return array Ordenes de venta
     */
    public static function getOrders(
        Int $SalesRep_ID = 0,
        Int $AD_Org_ID = null,
        Bool $IsTemp = true,
        Int $limit = 0
    )
    {
        $model = new ModelOrder;
        return $model->get_orders($SalesRep_ID, $AD_Org_ID, $IsTemp, $limit);
    }

    public static function init()
    {
        $jxnr = new Response;
        $jxnr
            ->setEvent('organizacion', 'onchange', rq('App.Jaxon.Organization')->call('getPricelist', pm()->select('organizacion')))
            ->setEvent('direccion', 'onchange', rq('App.Jaxon.BPartner')->call('getPhone', pm()->select('direccion')))
            ->setEvent('buscarCliente', 'onclick', rq('App.Jaxon.BPartner')->call('getBPartners', pm()->input('codigo_cliente')))
            ->setEvent('seleccionarCliente', 'onclick', rq('App.Jaxon.BPartner')->call('setBPartner', pm()->input('tercero')))
            ->setEvent('tercero_id', 'onchange', rq('App.Jaxon.Order')->call('changeBPartner', pm()->input('tercero_id')))
            ->setEvent('buscarProducto', 'onclick', rq('App.Jaxon.Product')->call('getProducts', pm()->input('codigo_producto'), pm()->form('pedidoDatos')));
        return $jxnr;
    }

    /**
     * Recalcular montos de la orden
     * 
     * @param int $SM_Order_ID Identificador de la orden
     * 
     * @return string/bool Mensaje de error / Estado del procedimiento
     */
    public static function syncAmt(
        Int $SM_Order_ID
    )
    {
        $model = new ModelOrder;
        return $model->sync_amt($SM_Order_ID);
    }

    /**
     * Reordenar lineas de una orden
     * 
     * @param int @SM_Order_ID Identificador de orden
     * 
     * @return string/array Mensaje de error / Estado del procedimiento
     */
    public static function syncLines(
        Int $SM_Order_ID
    )
    {
        $model = new ModelOrder;
        return $model->sync_lines($SM_Order_ID);
    }
}

?>