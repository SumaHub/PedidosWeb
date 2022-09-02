<?php

namespace App\Controller;

use App\Repository\Main\AdOrginfoRepository;
use App\Repository\Main\CBpartnerRepository;
use App\Repository\Main\MProductRepository;
use App\Repository\Main\MRequisitionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends BaseController
{
    /**
     * Ruta para ir al area de trabajo
     * @Route("/dashboard", name="dashboard")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * 
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function dashboard(Jaxon $jaxon, ManagerRegistry $manager): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $session = new Session();
        $organization = $session->get('organization', null);

        $ROrganizationinfo = new AdOrginfoRepository($manager);
        $RRequisition = new MRequisitionRepository($manager);
        $RBpartner = new CBpartnerRepository($manager);
        $product = new MProductRepository($manager);

        /** Criteria */
        $criteria_so = [
            'ad_org_id' => $organization->getAdOrgId(),
            'isquatation' => 'Y',
            'isactive' => 'Y'
        ];

        $orginfo = $ROrganizationinfo->findOneBy(['ad_org_id' => $organization->getAdOrgId()]);
        $criteria_p = [
            'sm_marca_id' => $orginfo->getSmMarcaId(),
            'issold' => 'Y',
            'isactive' => 'Y'
        ];

        $user = $session->get('user', null);
        if ( $user->getCBpartnerId() && $RBpartner->find($user->getCBpartnerId())->getIssalesrep() ) {
            $criteria_so['createdby'] = $user->getId();

            // Buscar terceros asignados
            $bpartner = $RBpartner->findBy(
                ['c_bpartner_id' => $RBpartner->findBySalesRep($user->getId())]
            );
        } else {
            $bpartner = $RBpartner->findBy([
                'isactive' => 'Y',
                'iscustomer' => 'Y',
                'ismatriz' => 'N',
                'issummary' => 'N'
            ]);
        }

        /** Response */
        return new Response(
            $this->twig->render('modules/dashboard.html.twig', [
                'title'         => 'Pedidos Web | Dashboard',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Dashboard',
                'user'          => $user,
                'organization'  => $organization,
                'stats'         => [
                    "requisitions"      => $RRequisition->findBy(  
                        array_merge( $criteria_so, ['docstatus' => ['CO', 'AP', 'IP', 'CL']] ), 
                        ['datedoc' => 'desc'], 
                        5 
                    ),
                    "requisitionsInDraft"   => $RRequisition->findBy( 
                        array_merge( $criteria_so, ['docstatus' => 'DR'] ), 
                        ['datedoc' => 'desc'], 
                        5 
                    ),
                    "orderQty"          => count($RRequisition->findBy( $criteria_so )),
                    "orderToApproveQty" => count($RRequisition->findBy( array_merge($criteria_so, [ 'docstatus' => 'AP' ]) )),
                    "bpartnerQty"       => count($bpartner),
                    "products"          => $product->findBy( $criteria_p, null, 5 ),
                    "productsFeatured"  => $product->findFeaturedProduct($orginfo->getSmMarcaId(), 5),
                    "productQty"        => count($product->findBy( $criteria_p ))
                ],
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
    }
}

?>