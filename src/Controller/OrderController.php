<?php

namespace App\Controller;

@session_start();

use App\Jaxon\Order;
use App\Jaxon\Organization;
use App\Util;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;

Class OrderController extends BaseController
{
    /**
     * Ruta para editar un pedido temporal
     * 
     * @param object $jaxon Jaxon\AjaxBundle\Jaxon
     * @param string $order_id Identificador de la orden
     * 
     * @return object Symfony\Component\HttpFoundation\Response
     */
    public function edit(Jaxon $jaxon, String $order_id)
    {
        if(Util::VerifySession()) return $this->logout();

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/order/edit.html', [
                'title'         => 'Pedidos Web | Pedido |' . $order_id,
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedidos',
                'breadcrumb'    => $order_id,
                'user'          => $_SESSION['user'],
                'order'         => Order::getOrder($order_id),
                'organizations' => Organization::getOrganizations($_SESSION['user']['ad_user_id']),
                'pricelists'    => Organization::setPricelist(),
                'terms'         => Organization::getPaymentTerms(),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }

    /**
     * Ruta para ver una lista con los pedidos aprobados
     * 
     * @param object $jaxon Jaxon\AjaxBundle\Jaxon
     * 
     * @return object Symfony\Component\HttpFoundation\Response
     */
    public function list(Jaxon $jaxon)
    {
        if(Util::VerifySession()) return $this->logout();

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/order/list.html', [
                'title'         => 'Pedidos Web | Pedidos',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedidos',
                'user'          => $_SESSION['user'],
                'orders'        => Order::getOrders(0, null, false),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        
        return $response;
    }

    /**
     * Ruta para crear un nuevo pedido
     * 
     * @param object $jaxon Jaxon\AjaxBundle\Jaxon
     * 
     * @return object Symfony\Component\HttpFoundation\Response
     */
    public function new(Jaxon $jaxon)
    {
        if(Util::VerifySession()) return $this->logout();

        unset($_SESSION['order']);
        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/order/new.html', [
                'title'         => 'Pedidos Web | Nuevo Pedido',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedido',
                'breadcrumb'    => 'Nuevo',
                'user'          => $_SESSION['user'],
                'organizations' => Organization::getOrganizations($_SESSION['user']['ad_user_id']),
                'pricelists'    => Organization::setPricelist(),
                'terms'         => Organization::getPaymentTerms(),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }

    /**
     * Ruta para ver un pedido aprobado
     * 
     * @param object $jaxon Jaxon\AjaxBundle\Jaxon
     * @param string $order_id Identificador de la orden
     * 
     * @return object Symfony\Component\HttpFoundation\Response
     */
    public function view(Jaxon $jaxon, String $order_id)
    {
        if(Util::VerifySession()) return $this->logout();

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/order/view.html', [
                'title'         => 'Pedidos Web | Pedido |' . $order_id,
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedidos',
                'breadcrumb'    => $order_id,
                'user'          => $_SESSION['user'],
                'order'         => Order::getOrder($order_id, false),
                'organizations' => Organization::getOrganizations($_SESSION['user']['ad_user_id']),
                'pricelists'    => Organization::setPricelist(),
                'terms'         => Organization::getPaymentTerms(),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>