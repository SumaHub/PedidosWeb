<?php

namespace App\Model;

use App\Constant;
use App\Controller\DBController;

class Invoice 
{
    /**
     * Devuelve un arreglo con los datos
     * de las facturas
     * 
     * @param int $AD_Org_ID Identificador de la organizacion
     * @param int $AD_User_ID Identificador del vendedor
     * @param int $C_Bpartner_ID Identificador del cliente
     * @param int $limit Limite de registros devueltos
     * 
     * @return ADORecordSet Datos de las facturas
     */
    public function get_open(
        Int $AD_Org_ID = 0,
        Int $AD_User_ID = 0,
        Int $C_BPartner_ID = 0,
        Int $DaysDue = 0,
        Int $NotificationNode = 0
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $invoices = $db->Execute(
                "SELECT DISTINCT
                    ci.C_Invoice_ID, 
                    cdt.Name AS DocType, ci.DocumentNo, arl_t.Name AS DocStatus, ci.NotificationNode,
                    ci.DateAcct::date, paymenttermduedate(ci.C_PaymentTerm_ID, ci.DateAcct)::date AS DueDate,
                    cpt.name AS PaymentTerm, daysbetween(current_timestamp, paymenttermduedate(ci.C_PaymentTerm_ID, ci.DateAcct)) AS DaysDue,
                    invoiceopentodate(ci.C_Invoice_ID, cips.C_InvoicePaySchedule_ID, current_date) AS DueAmt, ci.TotalLines, ci.GrandTotal,
                    au_s.Name AS SalesRep, cb.Name AS BPartner
                FROM C_Invoice ci
                JOIN C_BPartner cb ON cb.C_BPartner_ID = ci.C_BPartner_ID
                -- Terceros
                JOIN AD_User au_s ON au.AD_User_ID = ci.SalesRep_ID
                JOIN SM_Sales_Rep ssr ON ssr.C_BPartner_ID = cb.C_BPartner_ID 
                JOIN C_DocType cdt ON cdt.C_DocType_ID = ci.C_DocType_ID 
                -- Terminos de Pago
                JOIN C_InvoicePaySchedule cips ON cips.C_Invoice_ID = ci.C_Invoice_ID
                JOIN C_PaySchedule cps ON cps.C_PaySchedule_ID = cips.C_PaySchedule_ID
                JOIN C_PaymentTerm cpt ON cpt.C_PaymentTerm_ID = cps.C_PaymentTerm_ID
                -- Traduccion 
                JOIN AD_Ref_List arl ON arl.Value = ci.DocStatus AND arl.AD_Reference_ID = 131
                JOIN AD_Ref_List_Trl arl_t ON arl_t.AD_Ref_List_ID = arl.AD_Ref_List_ID AND arl_t.AD_Language = 'es_CO'
                -- Acceso
                JOIN AD_User_OrgAccess auo ON auo.AD_Org_ID = ci.AD_Org_ID 
                JOIN AD_User au ON au.AD_User_ID = auo.AD_User_ID
                LEFT JOIN C_BPartner cb_u ON cb_u.C_BPartner_ID = au.C_BPartner_ID
                WHERE 
                    ci.AD_Client_ID = " . Constant::AD_Client_ID . " AND ci.DocumentNo NOT LIKE 'SICXC%' 
                    AND ci.IsActive = 'Y' AND ci.IsPaid = 'N' AND ci.DocStatus = 'CO' AND cdt.DocBaseType IN ('ARI')
                    -- Solo se muestran aquellos documentos con saldo abierto de mas del 80%
                    AND invoiceopentodate(ci.C_Invoice_ID, cips.C_InvoicePaySchedule_ID, current_date) >= (ci.GrandTotal * 0.2)
                    AND au.AD_User_ID = $AD_User_ID AND auo.IsActive = 'Y'
                    AND CASE WHEN $AD_Org_ID > 0 THEN ci.AD_Org_ID = $AD_Org_ID ELSE ci.AD_Org_ID > 0 END
                    AND CASE WHEN $C_BPartner_ID > 0 THEN ci.C_BPartner_ID = $C_BPartner_ID ELSE ci.C_BPartner_ID > 0 END
                    -- Clientes o Facturas por vendedor
                    AND CASE 
                        WHEN cb_u.IsSalesRep = 'Y' THEN 
                            ssr.SalesRep_ID = au.AD_User_ID -- Todos los clientes del vendedor
                            -- au_s.AD_User_ID = $AD_User_ID -- Solo las facturas del vendedor
                        ELSE true 
                    END
                    -- Dias de Vencimiento
                    AND daysbetween(current_timestamp, paymenttermduedate(ci.C_PaymentTerm_ID, ci.DateAcct)) > 0
                    -- Busca las facturas notificadas previamente al nodo actual
                    AND CASE WHEN $NotificationNode IS NOT NULL
                        THEN ci.NotificationNode::numeric = $NotificationNode - 1
                        ELSE true
                    END
                ORDER BY ci.DateAcct::date DESC"
            ) ;
        } else {
            $msj    = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $invoices;
    }

    /**
     * Cambia el nodo de notificacion de una factura
     * 
     * @param int $C_Invoice_ID Identificador de la factura
     * @param int $NotificationNode Nodo de notificacion
     */
    public static function set_notification_node(
        Int $C_Invoice_ID,
        Int $NotificationNode = 0
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected())
            $code   = $db->Execute("UPDATE C_Invoice SET NotificationNode = NotificationNode::numeric + 1 WHERE C_Invoice_ID = $C_Invoice_ID AND NotificationNode::numeric < 3");
        else
            $msj    = $db->ErrorMsg();

        $db->Close();
        return $msj ?? $code;
    }
}
?>