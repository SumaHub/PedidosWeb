<?php

namespace App\Controller;

use App\Repository\CDoctypeRepository;
use App\Repository\COrderRepository;
use App\Repository\CPaymenttermRepository;
use App\Repository\MPricelistRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class OrderController extends BaseController
{

    /**
     * Ruta para ver una lista con los pedidos aprobados
     * @Route("/pedidos", name="orders")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function list(Jaxon $jaxon, ManagerRegistry $manager): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $response = new Response();

        /** Session Variables */
        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);

        /** Entity Repository */
        $ROrder = new COrderRepository($manager);

        $response->setContent(
            $this->twig->render('modules/order/list.html', [
                'title'         => 'Pedidos Web | Pedidos',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedidos',
                'user'          => $user,
                'organization'  => $organization,
                'orders'        => $ROrder->findBy(
                    [
                        'ad_org_id' => $organization->getAdOrgId(),
                        'issotrx' => 'Y',
                        'isactive' => 'Y'
                    ], 
                    ['dateordered' => 'desc'], 
                    30
                ),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        
        return $response;
    }

    /**
     * Ruta para crear un nuevo pedido
     * @Route("/pedido", name="order")
     * 
     * @param Jaxon\AjaxBundle\Jaxon $jaxon
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * 
     * @return Symfony\Component\HttpFoundation\Response Vista
     */
    public function new(Jaxon $jaxon, ManagerRegistry $manager): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $response = new Response();

        /** Session Variables */
        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);

        /** Entity Repository */
        $RDoctype = new CDoctypeRepository($manager);
        $RPaymentterm = new CPaymenttermRepository($manager);
        $RPricelist = new MPricelistRepository($manager);

        $response->setContent(
            $this->twig->render('modules/order/new.html', [
                'title'         => 'Pedidos Web | Nuevo Pedido',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedido',
                'breadcrumb'    => 'Nuevo',
                'user'          => $user,
                'organization'  => $organization,
                'doctype'       => $RDoctype->findBy([
                    'ad_client_id'  => [ $organization->getAdClientId() ],
                    'c_doctype_id'  => [1000068],
                    'docbasetype'   => 'SOO',
                    'issotrx'       => 'Y',
                    'isactive'      => 'Y'
                ]),
                'pricelist'     => $RPricelist->findBy([
                    'ad_org_id'     => $organization->getId(),
                    'issopricelist' => 'Y',
                    'isactive'      => 'Y'
                ]),
                'paymentterm'   => $RPaymentterm->findBy([
                    'ad_client_id'  => $organization->getAdClientId(),
                    'paymenttermusage'=> ['B', 'S'],
                    'isactive'      => 'Y'
                ]),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }

    /**
     * Ruta para ver un pedido
     * @Route("/pedido/{documentno}", name="pedido_view")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon 
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param string $documentno Identificador de la orden
     * 
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function view(Jaxon $jaxon, ManagerRegistry $manager, String $documentno): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $response = new Response();

        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);

        $ROrder = new COrderRepository($manager);
        $order = $ROrder->findBy( 
            ['documentno' => $documentno],
            null,
            1 
        );
        $this->session->set('order', $order[0]);

        $response->setContent(
            $this->twig->render('modules/order/view.html', [
                'title'         => 'Pedidos Web | Pedido | ' . $documentno,
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedidos',
                'breadcrumb'    => $documentno,
                'user'          => $user,
                'organization'  => $organization,
                'order'         => $order[0],
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>