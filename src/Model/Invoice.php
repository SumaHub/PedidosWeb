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
                "SELECT * FROM 
                    (SELECT DISTINCT
                        ao.AD_Org_ID, ao.Name AS Organization,
                        cb.C_BPartner_ID, cb.Name AS BPartner, cb.Saldo_CXC_USD, 
                        au_s.AD_User_ID, au_s.Name AS SalesRep,
                        ci.C_Invoice_ID, ci.DocumentNo,
                        cdt.Name AS DocType, arl_t.Name AS DocStatus, ci.NotificationNode,
                        ci.DateAcct::date, paymenttermduedate(ci.C_PaymentTerm_ID, ci.DateAcct)::date AS DueDate,
                        cpt.name AS PaymentTerm, daysbetween(current_timestamp, paymenttermduedate(ci.C_PaymentTerm_ID, ci.DateAcct)) AS DaysDue,
                        ( SELECT AVG(daysbetween(current_timestamp, paymenttermduedate(ci2.C_PaymentTerm_ID, ci2.DateAcct))) FROM C_Invoice ci2 JOIN C_DocType cdt2 ON cdt2.C_DocType_ID = ci2.C_DocType_ID WHERE ci2.C_BPartner_ID = cb.C_BPartner_ID AND ci2.IsSOTrx = 'Y' AND ci2.DocStatus IN ('CO', 'CL') AND ci2.IsPaid = 'N' AND cdt2.DocBaseType IN ('ARI') ) AS DaysDueAvg,
                        currencyconvert(invoiceopentodate(ci.C_Invoice_ID, cips.C_InvoicePaySchedule_ID, current_date), ci.C_Currency_ID, 100, ci.DateAcct, ci.C_ConversionType_ID, ci.AD_Client_ID, ci.AD_Org_ID) AS DueAmt, 
                        currencyconvert(ci.TotalLines, ci.C_Currency_ID, 100, ci.DateAcct, ci.C_ConversionType_ID, ci.AD_Client_ID, ci.AD_Org_ID) AS TotalLines, 
                        currencyconvert(ci.GrandTotal, ci.C_Currency_ID, 100, ci.DateAcct, ci.C_ConversionType_ID, ci.AD_Client_ID, ci.AD_Org_ID) AS GrandTotal
                    FROM C_Invoice ci
                    JOIN AD_Org ao ON ao.AD_Org_ID = ci.AD_Org_ID  
                    JOIN C_BPartner cb ON cb.C_BPartner_ID = ci.C_BPartner_ID
                    -- Terceros
                    JOIN AD_User au_s ON au_s.AD_User_ID = ci.SalesRep_ID
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
                        ci.AD_Client_ID = " . Constant::AD_Client_ID . " AND ci.IsActive = 'Y' AND ci.IsSOTrx = 'Y' AND ci.IsPaid = 'N' AND ci.DocStatus = 'CO' AND cdt.DocBaseType IN ('ARI')
                        -- Solo se muestran aquellos documentos con saldo abierto de mas del 80%
                        AND invoiceopentodate(ci.C_Invoice_ID, cips.C_InvoicePaySchedule_ID, current_date) >= (ci.GrandTotal * 0.2)
                        AND au.AD_User_ID = $AD_User_ID AND auo.IsActive = 'Y'
                        AND CASE WHEN $AD_Org_ID > 0 THEN ci.AD_Org_ID = $AD_Org_ID ELSE ci.AD_Org_ID > 0 END
                        AND CASE WHEN $C_BPartner_ID > 0 THEN ci.C_BPartner_ID = $C_BPartner_ID ELSE ci.C_BPartner_ID > 0 END
                        AND cb.IsCustomer = 'Y' AND cb.IsVendor = 'N' AND cb.IsEmployee = 'N' AND cb.IsSummary = 'N' AND cb.C_BP_Group_ID != 1000001
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
                        AND CASE 
                            WHEN $NotificationNode = 3 THEN ci.NotificationNode::numeric IN (2, 3)
                            WHEN $NotificationNode > 0 THEN ci.NotificationNode::numeric = $NotificationNode - 1
                            ELSE true
                        END
                    ORDER BY ao.AD_Org_ID, cb.C_BPartner_ID, ci.DateAcct::date DESC) i
                ORDER BY i.DaysDueAvg DESC, i.C_BPartner_ID"
            ) ;
        } else {
            $msj    = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $invoices;
    }

    /**
     * Devuelve un arreglo con los datos
     * de las facturas con notas de telecobranza
     * 
     * @param int $CreatedBy Identificador del operador
     * @param bool $IsSupervisor Es supervisor?
     * 
     * @return ADORecordSet Datos de las facturas 
     */
    public function get_to_collect(
        Int $CreatedBy = 0,
        Bool $IsSupervisor = false
    )
    {
        $db = DBController::conectar();
        $msj = null;
        $IsSupervisor = ($IsSupervisor) ? 'Y' : 'N';

        if ($db->IsConnected()) {
            $invoices = $db->Execute(
                "SELECT
                    DISTINCT
                    sn.sm_notastelecobranza_id as id,
                    sn.sm_notastelecobranza_id,
                    ao_m.name AS brand,
                    ao.name AS organization,
                    cb.name AS bpartner,
                    cd.name AS doctype,
                    ci.documentno,
                    ci.description,
                    ci.dateinvoiced::date,
                    adddays(ci.dateinvoiced::timestamp with time zone, cp.netdays) AS duedate,
                    coalesce(daysbetween(statement_timestamp(), ips.duedate::timestamp with time zone), paymenttermduedays(ci.c_paymentterm_id, ci.dateinvoiced::timestamp with time zone, statement_timestamp())) AS daysdue,
                    currencyconvert(invoiceopentodate(ci.C_Invoice_ID, null, adddays(CURRENT_DATE, 1)), ci.C_Currency_ID, 100, ci.DateAcct, coalesce(ci.C_ConversionType_ID, 1000000), ci.AD_Client_ID, ci.AD_Org_ID) AS openamt,
                    au_s.name AS rep_comercial,
                    sn.description AS nota,
                    sn.created::date AS ultimo_contacto,
                    sn.datenextaction::date AS proximo_contacto,
                    au.name AS operador
                FROM
                    sm_notastelecobranza sn
                    JOIN c_invoice ci ON ci.c_invoice_id = sn.c_invoice_id
                    LEFT JOIN c_invoicepayschedule ips ON ci.c_invoice_id = ips.c_invoice_id
                    JOIN ad_org ao ON ao.ad_org_id = ci.ad_org_id
                    JOIN ad_orginfo aoi_m ON aoi_m.ad_org_id = ao.ad_org_id
                    JOIN ad_org ao_m ON ao_m.ad_org_id = aoi_m.parent_org_id
                    JOIN c_bpartner cb ON cb.c_bpartner_id = ci.c_bpartner_id
                    JOIN c_currency cc ON cc.c_currency_id = ci.c_currency_id
                    JOIN ad_user au_s ON au_s.ad_user_id = ci.salesrep_id
                    JOIN c_doctype cd ON cd.c_doctype_id = ci.c_doctype_id
                    JOIN c_paymentterm cp ON cp.c_paymentterm_id = ci.c_paymentterm_id
                    JOIN ad_user au ON au.ad_user_id = sn.createdby
                WHERE
                    sn.isactive = 'Y'
                    AND sn.datenextaction::date = current_date
                    AND CASE WHEN '$IsSupervisor' = 'Y' THEN TRUE ELSE sn.createdby = $CreatedBy END
                ORDER BY ci.documentno, sn.sm_notastelecobranza_id"
            );
        } else {
            $msj = $db->ErrorMsg();
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