<?php

namespace App\Controller;

use App\Entity\Main\COrder;
use App\Repository\Main\AdOrgRepository;
use App\Repository\Main\AdUserRepository;
use App\Repository\Main\CDoctypeRepository;
use App\Repository\Main\COrderlineRepository;
use App\Repository\Main\COrderRepository;
use App\Repository\Main\CPaymenttermRepository;
use App\Repository\Main\MPricelistRepository;
use App\Repository\Main\MWarehouseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

Class OrderController extends BaseController
{

    public int $tableID = 259;

    public int $windowID = 143;

    public int $processID = 104;

    /**
     * Ruta para ver una lista con los pedidos aprobados
     * @Route("/pedidos", name="orders")
     * #TODO: Filtar ordenes por vendedores
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

        $organization = $this->session->get('organization', null);
        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $ROrder = new COrderRepository($manager);

        $user = $this->session->get('user', null);
        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/order/list.html.twig', [
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
     * @Route("/pedido", name="order_new")
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

        $organization = $this->session->get('organization', null);
        $warehouse = $this->session->get('warehouse', null);
        $user = $this->session->get('user', null);
        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $this->session->remove('order');
            
        $RDoctype = new CDoctypeRepository($manager);
        $RPaymentterm = new CPaymenttermRepository($manager);
        $RPricelist = new MPricelistRepository($manager);
        $RWarehouse = new MWarehouseRepository($manager);

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/order/new.html.twig', [
                'title'         => 'Pedidos Web | Nuevo Pedido',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedido',
                'breadcrumb'    => 'Nuevo',
                'user'          => $user,
                'organization'  => $organization,
                'warehouse'     => $warehouse,
                'warehouses'    => $RWarehouse->findBy([
                    'ad_org_id' => $organization->getId(),
                    'isactive'  => 'Y'
                ]),
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
     * Ruta para procesar los pedidos
     * @Route("/pedido/process/{id}", name="process_order")
     * 
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
     * @param Jaxon\AjaxBundle\Jaxon $jaxon
     * @param string $id Identificador de la orden
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Vista
     */
    public function process(ManagerRegistry $manager, HttpClientInterface $client, String $id): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();
        
        $id = base64_decode($id);
        $order = $manager->getRepository(COrder::class)->find($id);
        $response = $client->request('POST', 'https://localhost:8443/ADInterface/services/rest/model_adservice/run_process', [
            'headers' => [
                'Accept'    => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body'  => $this->twig->render('modules/order/process.json.twig', [
                'ws'    => $this->ws,
                'order' => $order
            ])
            
        ]);
        $content = json_decode( str_replace('@', '', $response->getContent()) );
        $status = $content->RunProcessResponse->IsError ? 'error' : 'success' ;
        return new Response(
            '{"status": '. $status .'}',
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * Ruta para ver un pedido
     * @Route("/pedido/{id}", name="order_view")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon 
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param string $id Identificador de la orden
     * 
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function view(Jaxon $jaxon, ManagerRegistry $manager, String $id): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();
 
        $organization = $this->session->get('organization', null);
        $user = $this->session->get('user', null);
        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $id = base64_decode($id);
        $ROrder = new COrderRepository($manager);
        $order = $ROrder->find($id);
        $this->session->set('order', $order);

        $ROrg = new AdOrgRepository($manager);
        $org = $ROrg->find( $order->getAdOrgId() );

        $ROrderline = new COrderlineRepository($manager);
        $orderlines = $ROrderline->findBy(
            ['c_order_id' => $order->getId(), 'isactive' => 'Y'], 
            ['line' => 'desc']
        );

        $RWarehouse = new MWarehouseRepository($manager);
        $warehouse = $RWarehouse->find( $order->getMWarehouseId() );

        $RUser = new AdUserRepository($manager);
        $salesrep = $RUser->find( $order->getSalesrepId() );

        $document = $order->getDocstatus() == 'DR' ? 'modules/order/edit.html.twig' : 'modules/order/view.html.twig';
        $response = new Response();
        $response->setContent(
            $this->twig->render($document, [
                'title'         => 'Pedidos Web | Pedido | ' . $order->getDocumentno(),
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Pedidos',
                'breadcrumb'    => $order->getDocumentno(),
                'user'          => $user,
                'organization'  => $organization,
                'warehouse'     => $warehouse,
                'salesrep'      => $salesrep,
                'org'           => $org,
                'order'         => $order,
                'orderlines'    => $orderlines,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>