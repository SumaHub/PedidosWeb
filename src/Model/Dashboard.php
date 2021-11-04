<?php

namespace App\Model;

use App\Controller\DBController;

class Dashboard
{
    public $msj = null;

    public $rs;

    public static function get_orders(
        String $DocStatus = 'CO, CL',
        Int $SalesRep_ID = null,
        Int $limit = 5
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()){
            $SalesRep_ID = $SalesRep_ID ?: $_SESSION['user']['ad_user_id'];
            $IsSalesRep = $_SESSION['user']['issalesrep'] ?: 'N';

            $orders = $db->Execute(
                "SELECT co.C_Order_ID, co.DocumentNo, cb.Name AS client, arl_t.name AS DocStatus, co.DateAcct::date AS DateAcct
                FROM C_Order co 
                JOIN C_BPartner cb ON cb.C_BPartner_ID = co.C_BPartner_ID 
                JOIN AD_Ref_List arl ON arl.Value = co.DocStatus AND arl.AD_Reference_ID = 131
                JOIN AD_Ref_List_Trl arl_t ON arl_t.AD_Ref_List_ID = arl.AD_Ref_List_ID AND arl_t.AD_Language = 'es_CO'
                WHERE 
                    co.issotrx = 'Y' AND co.docstatus IN ('CO', 'CL')
                    AND CASE WHEN '{$IsSalesRep}' = 'Y' THEN co.SalesRep_ID = {$SalesRep_ID} ELSE true END
                ORDER BY co.DateAcct DESC
                LIMIT {$limit}"
            );
        } 

        $db->Close();
        return (isset($orders) && $orders->RowCount() > 0) ? $orders : null ;
    }

    public static function get_stats()
    {
        return array(
            "ordersTemp"            => $_SESSION['user']['issalesrep'] == 'Y' ? Order::get_orders() : false,
            "orders"                => Order::get_orders(0, null, false),
            "products"              => Product::get_products(),
            "qtyOrders"             => Order::qty_orders(),
            "qtyOrdersToApprove"    => Order::qty_orders("'AP', 'IP'"),
            "qtyClients"            => BPartner::qty_clients(),
            "qtyProducts"           => Product::qty_products()   
        );
    }
}

?>