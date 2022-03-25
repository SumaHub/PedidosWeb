<?php

namespace App\Model;

use App\Constant;
use App\Controller\DBController;

class OrderLine
{
    private $AD_Table_ID = 260;

    private $SEQ_ID = 233;

    private $TEMP_SEQ_ID = 1000770;

    /**
     * Crea las lineas de las ordenes
     * 
     * @param int $SM_Orderline_ID Identificador de la linea temporal
     * @param int $C_Order_ID Identificador de la orden nueva
     * 
     * @return string|array Mensaje de error | Datos de la linea
     */
    public function create(
        Int $SM_Orderline_ID,
        Int $C_Order_ID
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){    
            // Obtener datos temporales
            $orderlineTemp = $db->GetRow("SELECT * FROM SM_Orderline WHERE SM_Orderline_ID = {$SM_Orderline_ID}");

            // Obtener arreglo de un registro vacio
            $record = $db->Execute("SELECT * FROM C_Orderline WHERE C_Orderline_ID = -1");

            // Valores por defecto
            list($date, $id, $uu) = array_values( $db->GetRow("SELECT current_timestamp, nextid({$this->SEQ_ID}, 'N'), uuid_generate_v4()") );

            // Construir arreglo con los datos reales
            $orderline = array_replace_recursive(
                $orderlineTemp,
                array(
                    "created"               => $date,   
                    "createdby"             => $_SESSION['user']['ad_user_id'],
                    "c_order_id"            => $C_Order_ID,
                    "c_orderline_id"        => $id,
                    "c_orderline_uu"        => $uu,
                    "updated"               => $date,
                    "updatedby"             => $_SESSION['user']['ad_user_id']
                )
            );

            // Generar y ejecutar SQL
            //$db->BeginTrans();
            //$code = ( $db->Execute( $db->GetInsertSQL($record, $orderline) ) ) ? $db->CommitTrans() : $db->RollbackTrans();
            $sql  = $db->GetInsertSQL($record, $orderline);
            
            // Verificar estatus de la conexcion
            //if(!$code) $msj = $db->ErrorMsg();
            
        } else {
            $msj  = $db->ErrorMsg();
        }

        return $sql;
    }

    /**
     * Crea las lineas de las ordenes temporales
     * 
     * @param array $order Datos de la orden
     * @param array $product Datos del producto
     * 
     * @return string|array Mensaje de error | Datos de la linea
     */
    public function create_temp(
        Array $order,
        Array $product
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){    
            // Obtener arreglo de un registro vacio
            $record = $db->Execute("SELECT * FROM SM_Orderline WHERE SM_Orderline_ID = -1");

            // Valores por defecto
            list($date, $id, $uu) = array_values( $db->GetRow("SELECT current_timestamp, nextid({$this->TEMP_SEQ_ID}, 'N'), uuid_generate_v4()") );

            // Construir arreglo con los datos reales
            $orderline = [];
            $orderline = array_replace_recursive(
                $order,
                $product,
                array(
                    "created"           => $date,   
                    "createdby"         => $_SESSION['user']['ad_user_id'],
                    "c_tax_id"          => Product::get_tax($product["m_product_id"]),
                    "line"              => Order::get_lineno($order['sm_order_id']),
                    "linenetamt"        => $product['pricelist'],
                    "qtyentered"        => 1,
                    "qtyordered"        => 1,
                    "sm_orderline_id"   => $id,
                    "sm_orderline_uu"   => $uu,
                    "updated"           => $date,
                    "updatedby"         => $_SESSION['user']['ad_user_id']
                )
            );

            // Generar y ejecutar SQL
            $db->BeginTrans();
            $code = ( $db->Execute( $db->GetInsertSQL($record, $orderline) ) ) ? $db->CommitTrans() : $db->RollbackTrans();

            // Verificar estatus de la conexcion
            if(!$code) $msj = $db->ErrorMsg();
            
        } else {
            $msj  = $db->ErrorMsg();
        }

        return $msj ?? $orderline;
    }

    /**
     * Borra una linea de una orden temporal
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * 
     * @return string|bool Mensaje de error | Resultado del procedimiento
     */
    public function delete(
        Int $SM_Orderline_ID
    )
    {
        $db  = DBController::conectar();
        $msj = null;
        
        if($db->IsConnected()) {
            $code = $db->Execute("UPDATE SM_Orderline SET IsActive = 'N' WHERE SM_Orderline_ID = {$SM_Orderline_ID}");

            if(!$code) $msj = $db->ErrorMsg();
        } else { 
            $msj = $db->ErrorMsg();
        }

        return $msj ?? $code ;
    }

    /**
     * Obtiene las lineas de una orden
     * 
     * @param int $Order_ID Identificador de la orden
     * @param bool $IsTemp Bandera de registros temporales
     * 
     * @return string|array Mensaje de Error | Arreglo de datos
     */
    public function get(
        Int $Order_ID,
        Bool $IsTemp = true
    )
    {
        $db         = DBController::conectar();
        $msj        = null;
        
        if($db->IsConnected()){
            $table      = $IsTemp ? "SM_Order" : "C_Order" ;
            $orderline  = $db->Execute(
                "SELECT 
                    t.{$table}_ID, t.{$table}line_ID, t.line, t.QtyEntered, t.PriceList::numeric, t.LineNetAmt,
                    mp.Name AS product, mp.SKU, mp.Value
                FROM {$table}line t 
                JOIN M_Product mp ON mp.M_Product_ID = t.M_Product_ID
                WHERE t.{$table}_ID = $Order_ID AND t.IsActive = 'Y'
                ORDER BY t.line DESC");
        } else {
            $msj    = $db->ErrorMsg();
        }

        return $msj ?? $orderline ;
    }

    /**
     * Obtener la cantidad de productos de una linea
     *
     * @param int $SM_Orderline_ID Identificador de linea
     * 
     * @return string Mensaje de error | Cantidad del producto
     */
    public function get_qty(
        Int $SM_Orderline_ID
    )
    {
        $db  = DBController::conectar();
        $msj = null;
        
        if($db->IsConnected())
            $qty = $db->GetOne("SELECT QtyOrdered FROM SM_Orderline WHERE SM_Orderline_ID = {$SM_Orderline_ID}");
        else 
            $msj = $db->ErrorMsg();

        return $msj ?? $qty ;
    }

    /**
     * Buscar producto en las lineas activas
     * de una orden
     * 
     * @param int $Order_ID Identificador de la orden
     * @param int $M_Product_ID Identificador del producto
     * 
     * @return array/bool Mensaje de error | Resultado de la busqueda
     */
    public function product_exist(
        Int $Order_ID,
        Int $M_Product_ID
    )
    {
        $db         = DBController::conectar();
        $msj        = null;
        
        if($db->IsConnected())
            $orderline  = $db->GetOne("SELECT COUNT(*) FROM SM_Orderline WHERE IsActive ='Y' AND SM_Order_ID = {$Order_ID} AND M_Product_ID = {$M_Product_ID}");
        else 
            $msj    = $db->ErrorMsg();

        return $msj ?? ($orderline > 0) ;
    }

    /**
     * Cambiar la cantidad de productos de una linea
     * en una orden previamente registrada en la BD
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * @param string $qty Cantidad
     * 
     * @return string|array Mensaje de error | Datos de la linea 
     */
    public function set_qty(
        Int $SM_Orderline_ID,
        String $qty
    )
    {
        $db  = DBController::conectar();
        $msj = null;
        
        if($db->IsConnected()){
            // Obtener arreglo de un registro vacio
            $record = $db->Execute("SELECT * FROM SM_OrderLine WHERE SM_OrderLine_ID = {$SM_Orderline_ID}");
            $record->FetchRow();

            // Valores por defecto
            $date = $db->GetOne("SELECT current_timestamp");
            
            // Construir arreglo con los datos reales
            $orderline = array(
                "linenetamt"        => $record->fields['pricelist'] * $qty,
                "qtyentered"        => $qty,
                "qtyordered"        => $qty,
                "updated"           => $date,
                "updatedby"         => $_SESSION['user']['ad_user_id']
            );

            // Generar y ejecutar SQL
            $db->BeginTrans();
            $code = ( $db->Execute( $db->GetUpdateSQL($record, $orderline) ) ) ? $db->CommitTrans() : $db->RollbackTrans();

            // Verificar estatus de la conexcion
            if(!$code) $msj = $db->ErrorMsg();
        } else { 
            $msj = $db->ErrorMsg();
        }

        return $msj ?? $orderline ;
    }
}
?>