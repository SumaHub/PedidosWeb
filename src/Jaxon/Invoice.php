<?php

namespace App\Jaxon;

use App\Model\Invoice as ModelInvoice;

class Invoice extends Base
{
    /**
     * Devuelve un arreglo con los datos
     * de las ordenes
     * 
     * @param int $AD_Org_ID Identificador de la organizacion
     * @param int $AD_User_ID Identificador del usuario
     * @param int $C_BPartner_ID Identificador del tercero
     * @param int $limit Limite de registros devueltos
     * 
     * @return ADORecordSet Facturas
     */
    public static function getOpen(
        Int $AD_Org_ID = 0,
        Int $AD_User_ID = 0,
        Int $C_BPartner_ID = 0,
        Int $DaysDue = 0,
        Int $NotificationNode = 0
    )
    {
        $invoice = new ModelInvoice;
        return $invoice->get_open($AD_Org_ID, $AD_User_ID, $C_BPartner_ID, $DaysDue, $NotificationNode);
    }

    public static function setNotificationNode(
        Int $C_Invoice_ID,
        Int $NotificationNode = 0
    )
    {
        $invoice = new ModelInvoice;
        return $invoice->set_notification_node($C_Invoice_ID, $NotificationNode);
    }
}

?>