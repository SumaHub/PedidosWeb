<?php

namespace App\Model;

use App\Constant;
use App\Controller\DBController;

class Order 
{
    private $AD_Table_ID = 259;

    private $C_DocType_ID = 1000068;

    public $code;

    private $DOC_SEQ_ID = 1000404;

    public $msj = null;

    public $rs;

    private $SEQ_ID = 232;

    private $TEMP_SEQ_ID = 1000769;

    public function create(
        Int $SM_Order_ID
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()) {
            // Obtener datos temporales
            $orderTemp = $db->GetRow("SELECT * FROM SM_Order WHERE SM_Order_ID = {$SM_Order_ID}");

            // Obtener arreglo de un registro vacio
            $record = $db->Execute("SELECT * FROM C_Order WHERE C_Order_ID = -1");

            // Valores por defecto
            list($date, $id, $uu, $documentno) = array_values( $db->GetRow("SELECT current_timestamp, nextid({$this->SEQ_ID}, 'N'), uuid_generate_v4(), (SELECT CONCAT('OV-', SM_Pref_Num, nextid_org({$this->DOC_SEQ_ID}, AD_Org_ID)) FROM AD_Org WHERE AD_Org_ID = {$orderTemp['ad_org_id']}) ") );

            // Construir arreglo con los datos reales
            $order = array_replace_recursive(
                $orderTemp,
                array(
                    "created"               => $date,   
                    "createdby"             => $_SESSION['user']['ad_user_id'],
                    "c_doctype_id"          => $this->C_DocType_ID,
                    "c_doctypetarget_id"    => $this->C_DocType_ID,
                    "c_order_id"            => $id,
                    "c_order_uu"            => $uu,
                    "dateacct"              => $date,
                    "documentno"            => $documentno,
                    "updated"               => $date,
                    "updatedby"             => $_SESSION['user']['ad_user_id']
                )
            );

            // Generar y ejecutar SQL
            $db->BeginTrans();
            $code = ( $db->Execute( $db->GetInsertSQL($record, $order) ) ) ? $db->CommitTrans() : $db->RollbackTrans();
            
            // Verificar estatus de la conexcion
            if(!$code) $msj = $db->ErrorMsg();

        } else {
            $msj  = $db->ErrorMsg();
        }

        return $msj ?? $order;
    }

    /**
     * Crear orden temporal en la BD
     * 
     * @param array $order Datos de la orden
     * 
     * @return string|array Mensaje de error | Datos de la orden creada temporalmente
     */
    public function create_temp(
        Array $order
    )
    {
        $db = DBController::conectar();
        $msj= null;
        
        if($db->IsConnected()) {
            // Obtener arreglo de un registro vacio
            $record = $db->Execute("SELECT * FROM SM_Order WHERE SM_Order_ID = -1");

            // Valores por defecto
            $date       = $db->GetOne("SELECT current_timestamp");

            // Construir arreglo con los datos reales
            $order = array(
                "ad_client_id"          => Constant::AD_Client_ID,
                "ad_org_id"             => $order['organizacion'],
                "c_bpartner_id"         => $order['tercero_id'] != "" ? $order['tercero_id'] : Constant::C_BPartner_ID,
                "c_bpartner_location_id"=> $order['direccion'] ?? Constant::C_BPartner_Location_ID,
                //"c_conversiontype_id"   => Constant::C_ConversionType_ID,
                "c_currency_id"         => $order['moneda'] ?? Constant::C_Currency_ID,
                "c_paymentterm_id"      => $order['termino'] ?? Constant::C_PaymentTerm_ID,
                "created"               => $date,
                "createdby"             => $_SESSION['user']['ad_user_id'],
                "dateordered"           => $date,
                "isactive"              => 'Y',
                "m_pricelist_id"        => $order['precio'],
                "m_warehouse_id"        => $_SESSION['organization']['m_warehouse_id'],
                "salesrep_id"           => $order['vendedor'] ?? $_SESSION['user']['ad_user_id'],
                "sm_order_id"           => $db->GetOne("SELECT nextid({$this->TEMP_SEQ_ID}, 'N')"),
                "sm_order_uu"           => $db->GetOne("SELECT uuid_generate_v4()"),
                "updated"               => $date,
                "updatedby"             => $_SESSION['user']['ad_user_id']
            );

            // Generar y ejecutar SQL
            $db->BeginTrans();
            $code = ( $db->Execute( $db->GetInsertSQL($record, $order) ) ) ? $db->CommitTrans() : $db->RollbackTrans();

            // Verificar estatus de la conexcion
            if(!$code) $msj = $db->ErrorMsg();

        } else {
            $msj  = $db->ErrorMsg();
        }

        return $msj ?? $order;
    }

    /**
     * Cambia el cliente de una orden
     * 
     * @param int $C_BPartner_ID Identificador del cliente
     * 
     * @return string|bool Mensaje de error | Estado de la consulta
     */
    public function change_bpartner(
        Int $C_BPartner_ID
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()) {
            $code   = $db->Execute("UPDATE SM_Order SET C_BPartner_ID = {$C_BPartner_ID} WHERE SM_Order_ID = {$_SESSION['order']['sm_order_id']}") ;

            if(!$code) $msj = $db->ErrorMsg();
        } else {
            $msj    = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $code;
    }

    /**
     * Elimina el pedido de las ordenes activas
     * 
     * @param int $Order_ID Identificador de la orden
     * @param bool $IsTemp Bandera de pedidos temporales
     * 
     * @return string|bool Mensaje de error | Estado de la consulta
     */
    public function delete(
        Int $Order_ID = 0,
        Bool $IsTemp = true
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()) {
            $table  = $IsTemp ? "SM_Order" : "C_Order" ;
            $code   = $db->Execute("UPDATE {$table} SET IsActive = 'N' WHERE {$table}_ID = {$Order_ID}") ;

            if(!$code) $msj = $db->ErrorMsg();
        } else {
            $msj    = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $code;
    }

    /**
     * Obtiene el siguiente numero de linea de una orden
     * 
     * @param int $Order_ID Identificador de la orden
     * @param bool $IsTemp Bandera de pedidos temporales
     * 
     * @return string|array Mensaje de error | Nro de linea
     */
    public static function get_lineno(
        Int $Order_ID,
        Bool $IsTemp = true
    )
    {
        $db = DBController::conectar();
        $msj = null;

        if($db->IsConnected()) {
            $table      = $IsTemp ? "SM_Order" : "C_Order" ;
            $line = $db->GetOne("SELECT (COUNT(*) + 1 ) * 10 FROM {$table}Line WHERE IsActive = 'Y' AND {$table}_ID = {$Order_ID}");
        } else {
            $msj = $db->ErrorMsg();
        }

        return $msj ?? $line;
    }

    /**
     * Devuelve un arreglo con los datos
     * de la ordene
     * 
     * @param int $Orden_ID Identificador de la orden
     * 
     * @return array Datos de la Orden
     */
    public function get_order(
        Int $Order_ID = 0, 
        Bool $IsTemp
    )
    {
        $db         = DBController::conectar();
        $bpartner   = new BPartner;
        $orderline  = new OrderLine;
        $msj        = null;

        if($db->IsConnected()){
            $table      = $IsTemp ? "SM_Order" : "C_Order" ;

            $order  = $db->GetRow(
                "SELECT 
                    t.{$table}_ID, t.DateOrdered,
                    t.AD_Client_ID, t.AD_Org_ID, 
                    t.SalesRep_ID,
                    cb.C_BPartner_ID, cb.Name AS bpartner, cb.Value,
                    cbl.C_BPartner_Location_ID, cbl.phone, cbl.email,
                    cl.C_Location_ID, cl.Address1,
                    t.C_Currency_ID, t.M_Warehouse_ID, 
                    t.M_PriceList_ID, t.C_PaymentTerm_ID,
                    t.TotalLines, t.GrandTotal, t.GrandTotal - t.TotalLines AS TaxAmt
                FROM $table t
                JOIN C_BPartner cb ON cb.C_BPartner_ID = t.C_BPartner_ID
                JOIN C_BPartner_Location cbl ON cbl.C_BPartner_Location_ID = t.C_BPartner_Location_ID
                JOIN C_Location cl ON cl.C_Location_ID = cbl.C_Location_ID
                WHERE {$table}_ID = {$Order_ID}"
            );
            $order['locations']  = $bpartner->get_location($order['value']);
            $order['orderlines'] = $orderline->get($Order_ID, $IsTemp);
        } else {
            $msj    = $db->ErrorMsg();
        }

        return $msj ?? $order ;
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
    public static function get_orders(
        Int $SalesRep_ID = 0,
        Int $AD_Org_ID = null,
        Bool $IsTemp = true,
        Int $limit = 0
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()){
            list($SalesRep_ID, $AD_Org_ID, $table)  = array( 
                $_SESSION['user']['issalesrep'] == 'Y' ? $_SESSION['user']['ad_user_id'] : $SalesRep_ID,
                $AD_Org_ID ?: $_SESSION['organization']['ad_org_id'],
                $IsTemp ? "SM_Order" : "C_Order"
            );

            $orders = $db->Execute(
                "SELECT 
                    $table.*, arl_t.Name AS DocStatus, $table.DateOrdered::date,
                    extract(DAY FROM current_timestamp - $table.DateOrdered) as diff_d,
                    extract(HOUR FROM current_timestamp - $table.DateOrdered) as diff_h,
                    extract(MINUTE FROM current_timestamp - $table.DateOrdered) as diff_m,
                    extract(SECOND FROM current_timestamp - $table.DateOrdered) as diff_s, 
                    cb.Name AS partner 
                FROM $table
                JOIN C_BPartner cb ON cb.C_BPartner_ID = $table.C_BPartner_ID
                JOIN AD_Ref_List arl ON arl.Value = $table.DocStatus AND arl.AD_Reference_ID = 131
                JOIN AD_Ref_List_Trl arl_t ON arl_t.AD_Ref_List_ID = arl.AD_Ref_List_ID AND arl_t.AD_Language = 'es_CO'
                WHERE 
                    $table.IsActive = 'Y' 
                    AND CASE 
                        WHEN $SalesRep_ID > 0 THEN $table.SalesRep_ID = $SalesRep_ID
                        ELSE true
                    END
                    AND CASE WHEN $AD_Org_ID > 0 THEN $table.AD_Org_ID = $AD_Org_ID ELSE true END
                ORDER BY $table.DateOrdered DESC
                LIMIT $limit") ;
        } else {
            $msj    = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $orders;
    }

    /**
     * Obtiene la cantidad de ordenes activas por vendedor
     * 
     * @param string $DocStatus Estatus de los documentos - DocStatus IN ('CO', 'CL')
     * @param int $SalesRep_ID Identificador del vendedor
     * 
     * @return int Cantidad de ordenes
     * 
     * @
     */
    public static function qty_orders(
        String $DocStatus = "'CO', 'CL'",
        Int $SalesRep_ID = null
    )
    {
        $db = DBController::conectar();
        $orders = null;

        if($db->IsConnected()){
            list($SalesRep_ID, $IsSalesRep, $AD_Org_ID) = array( $SalesRep_ID ?: $_SESSION['user']['ad_user_id'], $_SESSION['user']['issalesrep'], $_SESSION['organization']['ad_org_id'] ); 
            
            $orders = $db->GetOne(
                "SELECT COUNT(*) 
                FROM C_Order 
                WHERE IsActive = 'Y' AND IsSOTrx = 'Y' 
                AND AD_Org_ID = $AD_Org_ID
                AND DocStatus IN ($DocStatus)
                AND CASE WHEN '{$IsSalesRep}' = 'Y' THEN SalesRep_ID = {$SalesRep_ID} ELSE true END"
            );
        } 

        $db->Close();
        return $orders ?? 0 ;
    }

    /**
     * Recalcular montos de la orden
     * 
     * @param int $SM_Order_ID Identificador de la orden
     * 
     * @return string|bool Mensaje de error | Estado del procedimiento
     */
    public function sync_amt(
        Int $SM_Order_ID
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()) {
            // Obtener montos
            list($_SESSION['order']['totallines'], $_SESSION['order']['grandtotal']) = array_values( $db->GetRow("SELECT SUM(sol.linenetamt) AS totallines, SUM(sol.linenetamt) + SUM(sol.linenetamt / 100 * ct.Rate) AS grandtotal FROM SM_OrderLine sol JOIN C_tax ct ON ct.C_Tax_ID = sol.C_Tax_ID WHERE sol.IsActive = 'Y' AND sol.SM_Order_ID = {$SM_Order_ID}") );
            
            // Actualizart montos
            $code   = $db->Execute("UPDATE SM_Order SET TotalLines = {$_SESSION['order']['totallines']}, GrandTotal = {$_SESSION['order']['grandtotal']} WHERE SM_Order_ID = {$SM_Order_ID}") ;

            if(!$code) $msj = $db->ErrorMsg();
        } else {
            $msj    = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $code;
    }

    /**
     * Reordenar lineas de una orden
     * 
     * @param int @SM_Order_ID Identificador de orden
     * 
     * @return string|array Mensaje de error | Estado del procedimiento
     */
    public function sync_lines(
        Int $SM_Order_ID
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()) {
            $code   = $db->Execute(
                "UPDATE SM_Orderline
                SET line = sol.line
                FROM (SELECT ROW_NUMBER() OVER (ORDER BY SM_Orderline_ID) * 10 AS line, SM_Orderline_ID FROM SM_Orderline WHERE IsActive = 'Y' AND SM_Order_ID = {$SM_Order_ID}) sol
                WHERE sol.SM_Orderline_ID = SM_Orderline.SM_Orderline_ID"
            );

            if(!$code) $msj = $db->ErrorMsg();
        } else {
            $msj    = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $code;
    }
}
?>