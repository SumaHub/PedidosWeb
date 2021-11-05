<?php

namespace App\Controller;

@session_start();

use App\Jaxon\Invoice;
use App\Util;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;

Class InvoiceController extends BaseController
{
    /**
     * Ruta para ver una lista con el analisis de vencimiento
     * de las facturas
     * 
     * @param object $jaxon Jaxon\AjaxBundle\Jaxon
     * 
     * @return object Symfony\Component\HttpFoundation\Response
     */
    public function getOpen(Jaxon $jaxon)
    {
        if(Util::VerifySession()) return $this->logout();

        $response = new Response();
        $response->setContent( 
            $this->twig->render('modules/invoice/list.html', [
                'title'         => 'Pedidos Web | Facturas',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Facturas',
                'user'          => $_SESSION['user'],
                'invoices'      => Invoice::getOpen($_SESSION['organization']['ad_org_id'], $_SESSION['user']['ad_user_id']),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ]) 
        );

        return $response;
    }
}

?>