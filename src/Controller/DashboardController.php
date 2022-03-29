<?php

namespace App\Controller;

use App\Entity\AdOrginfo;
use App\Entity\COrder;
use App\Repository\CBpartnerRepository;
use App\Repository\MProductRepository;
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

        $response = new Response();
        $session = new Session();

        $organization = $session->get('organization', null);
        $organizationInfo = $manager->getRepository(AdOrginfo::class)->findBy(
            ['ad_org_id' => $organization->getAdOrgId()],
            null,
            1
        );
        $order = $manager->getRepository(COrder::class);
        $bpartner = new CBpartnerRepository($manager);
        $product = new MProductRepository($manager);

        /** Criteria */
        $criteria_so = [
            'ad_org_id' => $organization->getAdOrgId(),
            'issotrx' => 'Y',
            'isactive' => 'Y'
        ];
        $criteria_bp = [
            'isactive' => 'Y',
            'iscustomer' => 'Y',
            'ismatriz' => 'N',
            'issummary' => 'N'
        ];
        $criteria_p = [
            'sm_marca_id' => $organizationInfo[0]->getSmMarcaId(),
            'issold' => 'Y',
            'isactive' => 'Y'
        ];

        $user = $session->get('user', null);
        if ( $user->getCBpartnerId() && $bpartner->find($user->getCBpartnerId())->getIssalesrep() ) {
            $criteria_so['salesrep_id'] = $user->getId();

            // Buscar terceros asignados
            $bpartner = $bpartner->findBy(
                ['c_bpartner_id' => $bpartner->findBySalesRep($user->getId())]
            );
        } else {
            $bpartner = $bpartner->findBy( $criteria_bp );
        }

        /** Response */
        $response->setContent(
            $this->twig->render('modules/dashboard.html', [
                'title'         => 'Pedidos Web | Dashboard',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Dashboard',
                'user'          => $user,
                'organization'  => $organization,
                'stats'         => [
                    "orders"            => $order->findBy( $criteria_so, ['dateordered' => 'desc'], 5 ),
                    "ordersInDraft"     => $order->findBy( $criteria_so, ['dateordered' => 'desc'], 5 ),
                    "orderQty"          => count($order->findBy( $criteria_so )),
                    "orderToApproveQty" => count($order->findBy( array_merge($criteria_so, [ 'docstatus' => 'AP' ]) )),
                    "bpartnerQty"       => count($bpartner),
                    "products"          => $product->findBy( $criteria_p, null, 5 ),
                    "productsFeatured"  => $product->findFeaturedProduct($organization->getId(), 5),
                    "productQty"        => count($product->findBy( $criteria_p ))
                ],
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        
        return $response;
    }
}

?>