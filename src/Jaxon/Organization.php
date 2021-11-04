<?php

namespace App\Jaxon;

use App\Model\Organization as ModelOrg;
use Jaxon\Response\Response;

class Organization extends Base
{
    /**
     * Obtiene las organizaciones a las que el 
     * usuario tiene acceso en el sistema iDempiere
     * 
     * @param int $AD_User_ID Identificador del Usuario
     * 
     * @return string|ADORecordSet Mensaje de error | Organizaciones
     */
    public static function getOrganizations(
        Int $AD_User_ID = null
    )
    {
        $model = new ModelOrg;
        return $model->get_organizations($AD_User_ID ?: $_SESSION['user']['ad_user_id']);
    }

    public static function getPaymentTerms()
    {
        $model = new ModelOrg;
        return $model->get_paymentterms();
    }

    public function getPricelist(Int $organization)
    {
        $jxnr       = new Response;
        $model      = new ModelOrg;
        $priceList  = $model->get_pricelist($organization);
        $html       = '<option value="0">-- Seleccione una Opci&oacute;n --</option>';
        foreach ($priceList as $list) {
            $html .= '<option value="'.$list['m_pricelist_id'].'">'.$list['nombre'].'</option>';
        }
        $jxnr->assign('precio', 'innerHTML', $html);
        return $jxnr;
    }

    public static function getWarehouse(Int $organization = null)
    {
        $jxnr   = new Response;
        $model  = new ModelOrg;

        $warehouses = $model->get_warehouses($organization);
        $html       = '';
        foreach ($warehouses as $warehouse) {
            $html .= '<option value="'. $warehouse['m_warehouse_id'] .'">'. $warehouse['name'] .'</option>';
        }
        return $jxnr->assign('warehouse', 'innerHTML', $html);
    }

    public static function init()
    {
        $jxnr = new Response;
        $jxnr
            ->setEvent('organization', 'onchange', rq('App.Jaxon.Organization')->call('getWarehouse', pm()->select('organization')))
            ->setEvent('submit', 'onclick', rq('App.Jaxon.Organization')->call('setOption', pm()->form('option')));
        return $jxnr;
    }

    public function setOption(Array $options)
    {
        $jxnr = new Response;
        $model= new ModelOrg;
        $_SESSION['organization'] = $model->get_organization($options['organization'], $options['warehouse']);
        return $jxnr->redirect("/dashboard");
    }

    public static function setPricelist(Int $organization = null)
    {
        $model = new ModelOrg;

        $priceList      = $model->get_pricelist($organization ?? $_SESSION['organization']['ad_org_id']);
        $html           = '';
        foreach ($priceList as $list) {
            $html .= '<option value="'.$list['m_pricelist_id'].'">'.$list['nombre'].'</option>';
        }
        return $html;
    }

    public static function setWarehouse(Int $organization = null)
    {
        $model = new ModelOrg;

        // Si no se suministra el parametro de organizacion
        if( is_null($organization) ) {
            $organizations  = self::getOrganizations(); 
            $organizations  = $organizations->fetchRow();
            $organization   = $organizations['ad_org_id'];
        } 

        $warehouses = $model->get_warehouses($organization ?: $organizations['ad_org_id']);
        $html       = '';
        foreach ($warehouses as $warehouse) {
            $html .= '<option value="'. $warehouse['m_warehouse_id'] .'">'. $warehouse['name'] .'</option>';
        }
        return $html;
    }
}

?>