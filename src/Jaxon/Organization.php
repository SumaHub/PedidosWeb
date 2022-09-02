<?php

namespace App\Jaxon;

use App\Entity\Main\AdOrg;
use App\Entity\Main\AdRole;
use App\Entity\Main\MWarehouse;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Organization extends Base implements Template
{
    public function get(Int $id)
    {
        return $this->manager->getRepository(AdOrg::class)->find($id);
    }

    public function getAll(Int $foreign_key = 0)
    {
        
    }

    /**
     * Obtiene todos los almacenes de una organizacion
     * @param int $ad_org_id Identificador de la organizacion
     * @return array
     */
    public function getWarehouses(Int $ad_org_id)
    {
        $jxnr = new Response;
        $organization = $this->manager->getRepository(AdOrg::class)->find($ad_org_id);
        $warehouses = $organization->getMWarehouse();

        $html = '';
        foreach ($warehouses as $warehouse) {
            $html .= '<option value="' . $warehouse->getMWarehouseId() . '">' . $warehouse->getName() . '</option>';
        }
        $jxnr->assign('warehouse', 'innerHTML', $html);
        return $jxnr;
    }
    
    public static function init()
    {
        $jxnr = new Response;
        $jxnr
            ->setEvent('organization', 'onchange', rq('App.Jaxon.Organization')->call('getWarehouses', pm()->select('organization')))
            ->setEvent('submit', 'onclick', rq('App.Jaxon.Organization')->call('setOption', pm()->form('option')));
        return $jxnr;
    }

    public function setOption(Array $options)
    {
        $jxnr = new Response;
        $session = new Session();

        if( empty($options['organization']) || $options['organization'] == 0 )
            return $jxnr->alert('Por favor, seleccione una organizacion!');

        $session->set('role', $this->manager->getRepository(AdRole::class)->find($options['role']));
        $session->set('organization', $this->manager->getRepository(AdOrg::class)->find($options['organization']));
        $session->set('warehouse', $this->manager->getRepository(MWarehouse::class)->find($options['warehouse']));

        return $jxnr->redirect("/dashboard");
    }
}

?>