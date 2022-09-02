<?php

namespace App\Controller;

use App\Repository\Main\CBpartnerRepository;
use App\Repository\Main\CInvoiceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class InvoiceController extends BaseController
{
    /**
     * Ruta para ver una lista con las facturas
     * @Route("/facturas", name="invoices")
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

        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);

        $RInvoice = new CInvoiceRepository($manager);
        $criteria = [
            'ad_org_id' => $organization->getAdOrgId(),
            'issotrx' => 'Y',
            'isactive' => 'Y'
        ];

        $RBPartner = new CBpartnerRepository($manager);
        if ( $user->getCBpartnerId() > 0 ) {
            $partner = $RBPartner->find( $user->getCBpartnerId() );
            if ( $partner->getIssalesrep() )
                $criteria['salesrep_id'] = $partner->getId();
        }

        $response->setContent(
            $this->twig->render('modules/invoice/list.html', [
                'title'         => 'Pedidos Web | Facturas',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Facturas',
                'user'          => $user,
                'organization'  => $organization,
                'invoices'      => $RInvoice->findBy(
                    $criteria, 
                    ['dateacct' => 'desc'],
                    100
                ),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );

        return $response;
    }

    /**
     * Ruta para ver una factura
     * @Route("/factura/{documentno}/{id}", name="invoice_view")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon 
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param string $documentno Nro del documento
     * @param string $id Identificador de la factura
     * 
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function view(Jaxon $jaxon, ManagerRegistry $manager, String $documentno, String $id): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $response = new Response();

        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);

        $RInvoice = new CInvoiceRepository($manager);
        $response->setContent(
            $this->twig->render('modules/invoice/view.html', [
                'title'         => 'Pedidos Web | Factura | ' . $documentno,
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Facturas',
                'breadcrumb'    => $documentno,
                'user'          => $user,
                'organization'  => $organization,
                'invoice'       => $RInvoice->find( base64_decode($id) ),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>