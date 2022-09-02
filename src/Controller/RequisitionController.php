<?php

namespace App\Controller;

use App\Entity\Main\MRequisition;
use App\Repository\Main\AdOrgRepository;
use App\Repository\Main\AdUserRepository;
use App\Repository\Main\CDoctypeRepository;
use App\Repository\Main\CPaymenttermRepository;
use App\Repository\Main\MPricelistRepository;
use App\Repository\Main\MRequisitionlineRepository;
use App\Repository\Main\MRequisitionRepository;
use App\Repository\Main\MWarehouseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

Class RequisitionController extends BaseController
{

    public int $tableID = 702;

    public int $windowID = 1000068;

    public int $processID = 273;

    /**
     * Ruta para ver una lista con las plantillas de venta
     * @Route("/plantillas", name="requisitions")
     * #TODO: Filtar plantillas por vendedores
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
        $user = $this->session->get('user', null);
        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $RRequisition = new MRequisitionRepository($manager);

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/requisition/list.html.twig', [
                'title'         => 'Pedidos Web | Plantillas',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Plantillas de Ventas',
                'user'          => $user,
                'organization'  => $organization,
                'requisitions'  => $RRequisition->findBy(
                    [
                        'ad_org_id' => $organization->getAdOrgId(),
                        'isquatation'=> 'Y',
                        'isactive' => 'Y'
                    ], 
                    ['datedoc' => 'desc'], 
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
     * Ruta para crear una nueva plantilla
     * @Route("/plantilla", name="requisition_new")
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
        
        $this->session->remove("requisition");
            
        $RDoctype = new CDoctypeRepository($manager);
        $RPaymentterm = new CPaymenttermRepository($manager);
        $RPricelist = new MPricelistRepository($manager);
        $RWarehouse = new MWarehouseRepository($manager);

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/requisition/new.html.twig', [
                'title'         => 'Pedidos Web | Nuevo Plantilla',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Plantilla',
                'breadcrumb'    => 'Nuevo',
                'user'          => $user,
                'organization'  => $organization,
                'warehouse'     => $warehouse,
                'warehouses'    => $RWarehouse->findBy([
                    'ad_org_id' => $organization->getId(),
                    'isactive'  => 'Y'
                ]),
                'doctype'       => $RDoctype->find(1000241),
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
     * Ruta para procesar los plantilla
     * @Route("/plantilla/process/{id}", name="process_requisition")
     * 
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param \Symfony\Contracts\HttpClient\HttpClientInterface $client
     * @param Jaxon\AjaxBundle\Jaxon $jaxon
     * @param string $id Identificador de la plantilla
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Vista
     */
    public function process(ManagerRegistry $manager, HttpClientInterface $client, String $id): Response
    {        
        $id = base64_decode($id);
        $requisition = $manager->getRepository(MRequisition::class)->find($id);
        $response = $client->request(
            'POST', 
            'https://localhost:8000/ADInterface/services/rest/model_adservice/run_process', 
            [
                'headers' => [
                    'Accept'    => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'body'  => $this->twig->render('modules/requisition/process.json.twig', [
                    'ws'    => $this->ws,
                    'requisition' => $requisition
                ])
            ]
        );

        $content = json_decode( str_replace('@', '', $response->getContent()) );
        if ( !isset($content->RunProcessResponse->IsError) && $content->RunProcessResponse->IsError ) {
            $response = array(
                'status' => 'error',
                'msg' => $content->RunProcessResponse->Error
            );
        } else {
            $response = array(
                'status' => 'success',
                'recordId' => $id,
                'msg' => $content->RunProcessResponse->Summary,
            );
        }
        
        return new Response(
            json_encode($response),
            Response::HTTP_OK,
            ['content-type' => 'application/json']
        );
    }

    /**
     * Ruta para ver un plantilla
     * @Route("/plantilla/{id}", name="requisition_view")
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
            return $this->redirectToRoute('dashboard');

        $id = base64_decode($id);
        $RRequisition = new MRequisitionRepository($manager);
        $requisition = $RRequisition->find($id);
        if( is_null($requisition) )
            return $this->redirectToRoute('dashboard');

        $this->session->set('requisition', $requisition);

        $ROrg = new AdOrgRepository($manager);
        $org = $ROrg->find( $requisition->getAdOrgId() );

        $RRequisitionline = new MRequisitionlineRepository($manager);
        $requisitionlines = $RRequisitionline->findBy(
            ['m_requisition_id' => $requisition->getId(), 'isactive' => 'Y'], 
            ['line' => 'desc']
        );

        $RWarehouse = new MWarehouseRepository($manager);
        $warehouse = $RWarehouse->find( $requisition->getMWarehouseId() );

        $RUser = new AdUserRepository($manager);
        $salesrep = $RUser->find( $requisition->getSalesrepId() );

        $document = $requisition->getDocstatus() == 'DR' ? 'modules/requisition/edit.html.twig' : 'modules/requisition/view.html.twig';
        $response = new Response();
        $response->setContent(
            $this->twig->render($document, [
                'title'         => 'Pedidos Web | Plantilla | ' . $requisition->getDocumentno(),
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Plantilla',
                'breadcrumb'    => $requisition->getDocumentno(),
                'user'          => $user,
                'organization'  => $organization,
                'warehouse'     => $warehouse,
                'salesrep'      => $salesrep,
                'org'           => $org,
                'requisition'   => $requisition,
                'requisitionlines'  => $requisitionlines,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>