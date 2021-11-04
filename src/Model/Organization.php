<?php

namespace App\Model;

use App\Constant;
use App\Controller\DBController;

class Organization 
{
    /**
     * Obtiene la informacion de una organizacion y almacen
     * 
     * @param int $AD_Org_ID Identificador de la organizacion
     * @param int $M_Warehouse_ID Identificador del almacen
     * 
     * @return string|array Mensaje de error | Datos de organizacion
     */
    public function get_organization(
        Int $AD_Org_ID,
        Int $M_Warehouse_ID
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $organization = $db->GetRow(
                "SELECT
                    ao.AD_Org_ID, ao.Name AS Organization, ao.SM_Pref_Num, aoi.TaxID, aoi.SM_Marca_ID, mw.M_Warehouse_ID, mw.Name AS Warehouse
                FROM AD_Org ao 
                JOIN AD_Orginfo aoi ON aoi.AD_Org_ID = ao.AD_Org_ID 
                JOIN M_Warehouse mw ON mw.AD_Org_ID = ao.AD_Org_ID 
                WHERE ao.AD_Org_ID = {$AD_Org_ID} AND mw.M_Warehouse_ID = {$M_Warehouse_ID}"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $organization;
    }

    /**
     * Obtiene las organizaciones a las que el 
     * usuario tiene acceso en el sistema iDempiere
     * 
     * @param int $AD_User_ID Identificador del Usuario
     * 
     * @return string|ADORecordSet Mensaje de error | Organizaciones
     */
    public function get_organizations(
        Int $AD_User_ID
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $AD_Org_ID = $_SESSION['organization']['ad_org_id'] ?? 0;
            $organizations = $db->Execute("SELECT wuo.AD_Org_ID as id, wuo.AD_Org_ID, wuo.Organization as nombre, CASE WHEN wuo.AD_Org_ID = {$AD_Org_ID} THEN 'Y' ELSE 'N' END AS IsDefault FROM WS_User_OrgAccess_v wuo WHERE wuo.AD_User_ID = {$AD_User_ID} AND wuo.AD_Org_ID > 0 AND wuo.IsSummary = 'N' ORDER BY wuo.Organization");
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $organizations;
    }

    /**
     * Obtiene los terminos de pagos activos para las ventas
     * 
     * @return string|array Mensaje de error | Terminos de pago
     */
    public function get_paymentterms()
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $paymentterms = $db->Execute("SELECT cpt.C_PaymentTerm_ID, cpt.Name as nombre, CASE WHEN cpt.C_PaymentTerm_ID = ". Constant::C_PaymentTerm_ID ." THEN 'Y' ELSE 'N' END AS IsDefault FROM C_PaymentTerm cpt WHERE cpt.IsActive = 'Y' AND cpt.PaymentTermUsage IN ('B', 'S') AND cpt.C_PaymentTerm_ID NOT IN (1000032, 1000033)");
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $paymentterms;
    }

    /**
     * Obtiene las listas de precio por organizacion
     * 
     * @param int $AD_Org_ID Identificador de la organizacion
     * 
     * @return string|array Mensaje de error | Listas de precio
     */
    public function get_pricelist(
        Int $AD_Org_ID
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $pricelists = $db->Execute("SELECT mp.M_PriceList_ID, mp.Name as nombre FROM M_PriceList mp WHERE mp.IsActive = 'Y' AND mp.IsSOPriceList = 'Y' AND mp.AD_Org_ID = {$AD_Org_ID}");
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $pricelists;
    }

    /**
     * Obtiene los almacenes activos por organizacion
     * 
     * @param int $AD_Org_ID Identificador de la organizacion
     * 
     * @return string|array Mensaje de error | Almacenes
     */
    public function get_warehouses(
        Int $AD_Org_ID
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $warehouses = $db->Execute("SELECT * FROM M_Warehouse mw WHERE mw.IsActive = 'Y' AND mw.AD_Org_ID = {$AD_Org_ID} ORDER BY mw.Name");
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $warehouses;
    }
}
?>