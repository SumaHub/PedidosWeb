<?php

namespace App\Model;

use App\Controller\DBController;

class BPartner
{
    public $msj = null;

    public $rs;

    public function get_bpartner(
        String $code = '', 
        Int $user = 0
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()){
            $this->rs = $db->Execute(
                "SELECT cb.C_BPartner_ID, cb.Name AS nombre, cb.Value AS codigo
                FROM SM_Sales_Rep ssr 
                    JOIN C_BPartner cb ON cb.C_BPartner_ID = ssr.C_BPartner_ID
                WHERE 
                    cb.IsActive = 'Y' AND cb.IsCustomer = 'Y' 
                    AND CASE WHEN '{$code}' != '' THEN LOWER(cb.Name) LIKE LOWER('%{$code}%') OR cb.Value LIKE '%{$code}%' ELSE true END
                    AND CASE WHEN {$user} > 0 THEN ssr.SalesRep_ID = {$user} ELSE true END"
            );
        } else {
            $this->msj = $db->ErrorMsg();
        }

        $db->Close();
        return (is_null($this->msj)) ? $this->rs : $this->msj ;
    }

    public function get_location(
        String $code
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $location = $db->Execute(
                "SELECT cbl.C_BPartner_Location_ID, cl.Address1
                FROM C_BPartner cb
                JOIN C_BPartner_Location cbl ON cbl.C_BPartner_ID = cb.C_BPartner_ID
                JOIN C_Location cl ON cl.C_Location_ID = cbl.C_Location_ID
                WHERE cb.IsActive = 'Y' AND cb.IsCustomer = 'Y' AND cb.Value = '{$code}' AND cbl.IsActive = 'Y' AND cbl.IsBillTo = 'Y'"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $location ;
    }

    public function get_phone(
        Int $location = null
    )
    {
        $db = DBController::conectar();
        
        if($db->IsConnected()){
            $this->rs = $db->GetOne("SELECT cbl.Phone FROM C_BPartner_Location cbl WHERE cbl.C_BPartner_Location_ID = {$location}");
        } else {
            $this->msj = $db->ErrorMsg();
        }

        $db->Close();
        return (is_null($this->msj)) ? $this->rs : $this->msj ;
    }

    public static function qty_clients(
        Int $SalesRep_ID = null
    )
    {
        $db = DBController::conectar();

        if($db->IsConnected()){
            list($SalesRep_ID, $IsSalesRep) = array( $SalesRep_ID ?: $_SESSION['user']['ad_user_id'], $_SESSION['user']['issalesrep'] );

            $clients = $db->GetOne(
                "SELECT COUNT(*) 
                FROM C_BPartner cb 
                JOIN SM_Sales_Rep ssr ON ssr.C_BPartner_ID = cb.C_BPartner_ID 
                WHERE cb.IsActive = 'Y' AND cb.IsCustomer = 'Y' 
                AND CASE WHEN '{$IsSalesRep}' = 'Y' THEN ssr.SalesRep_ID = {$SalesRep_ID} ELSE true END"
            );
        } 

        $db->Close();
        return (isset($clients) && $clients > 0) ? $clients : 0 ;
    }
}
?>