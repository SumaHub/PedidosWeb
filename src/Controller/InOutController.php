<?php

namespace App\Controller;

use App\Repository\CBpartnerRepository;
use App\Repository\MInoutRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class InOutController extends BaseController
{
    /**
     * Ruta para ver una lista con las entregas
     * @Route("/entregas", name="shipments")
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

        $RInOut = new MInoutRepository($manager);
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
            $this->twig->render('modules/inout/list.html', [
                'title'         => 'Pedidos Web | Entregas',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Entregas',
                'user'          => $user,
                'organization'  => $organization,
                'shipments'      => $RInOut->findBy(
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
     * Ruta para ver una entrega
     * @Route("/entrega/{documentno}/{id}", name="shipment_view")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon 
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param string $documentno Nro del documento
     * @param string $id Identificador de la entrega
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

        $RInOut = new MInoutRepository($manager);
        $response->setContent(
            $this->twig->render('modules/inout/view.html', [
                'title'         => 'Pedidos Web | Entrega | ' . $documentno,
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Entregas',
                'breadcrumb'    => $documentno,
                'user'          => $user,
                'organization'  => $organization,
                'shipmet'       => $RInOut->find( base64_decode($id) ),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>