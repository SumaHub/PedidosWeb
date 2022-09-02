<?php

namespace App\Controller;

use App\Repository\Main\CBpartnerLocationRepository;
use App\Repository\Main\CBpartnerRepository;
use App\Repository\Main\COrderRepository;
use App\Repository\Main\MInoutRepository;
use App\Repository\Main\MRequisitionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends BaseController
{

    public int $tableID = 259;

    public int $windowID = 123;

    /**
     * Ruta para ver los terceros
     * @Route("/clientes", "customers")
     * 
     * @param Jaxon $jaxon
     * @param ManagerRegistry $manager
     * 
     * @return Response Vista
     */
    public function list(Jaxon $jaxon, ManagerRegistry $manager): Response
    {
        if( !$this->VerifySession() )
            return $this->logout();

        $organization = $this->session->get('organization');
        $user = $this->session->get('user');
        $role = $this->session->get('role');
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $RBpartner = new CBpartnerRepository($manager);
        if ( $user->getCBpartnerId() && $RBpartner->find( $user->getCBpartnerId() )->getIssalesrep() )
            $partners = $RBpartner->findBySalesRep( $user->getId() );

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/partner/list.html.twig', [
                'title'         => 'Pedidos Web | Clientes',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Clientes',
                'user'          => $user,
                'partners'      => $partners,
                'organization'  => $organization,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );

        return $response;
    }

    /**
     * Ruta para ver un tercero
     * @Route("/cliente/{id}", "customers")
     * 
     * @param Jaxon $jaxon
     * @param ManagerRegistry $manager
     * 
     * @return Response Vista
     */
    public function view(Jaxon $jaxon, ManagerRegistry $manager, String $id): Response
    {
        if( !$this->VerifySession() )
            return $this->logout();

        $organization = $this->session->get('organization');
        $user = $this->session->get('user');
        $role = $this->session->get('role');
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $id = base64_decode($id);
        $RBpartner = new CBpartnerRepository($manager);
        $partner = $RBpartner->find($id);

        /** Ordenes de Venta */
        $ROrder = new COrderRepository($manager);
        $orders = $ROrder->findBy(['c_bpartner_id' => $partner->getId()]);

        /** Plantillas de Venta */
        $RRequisition = new MRequisitionRepository($manager);
        $requisitions = $RRequisition->findBy(['c_bpartner_id' => $partner->getId()]);

        /** Notas de Entrega */
        $RInOut = new MInoutRepository($manager);
        $inouts = $RInOut->findBy(['c_bpartner_id' => $partner->getId()]);

        $RBpartnerLocation = new CBpartnerLocationRepository($manager);
        $locations = $RBpartnerLocation->findBy(['c_bpartner_id' => $partner->getId()]);

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/partner/view.html.twig', [
                'title'         => 'Pedidos Web | Cliente | ' . $partner->getValue(),
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Clientes',
                'breadcrumb'    => $partner->getValue(),
                'user'          => $user,
                'partner'       => $partner,
                'status'        => $this->status,
                'locations'     => $locations,
                'orders'        => $orders,
                'requisitions'  => $requisitions,
                'inouts'        => $inouts,
                'organization'  => $organization,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );

        return $response;
    }
}

?>