<?php

namespace App\Controller;

use App\Repository\AdOrginfoRepository;
use App\Repository\MProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{
    /**
     * Ruta para ver todos los productos
     * @Route("/productos", name="productos")
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

        /** Document Variable */
        $ROrg = new AdOrginfoRepository($manager);
        $organizationInfo = $ROrg->findBy(
            ['ad_org_id' => $organization->getAdOrgId()],
            null,
            1
        );

        $RProduct = new MProductRepository($manager);
        $response->setContent(
            $this->twig->render('modules/product/list.html', [
                'title'         => 'Pedidos Web | Productos',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Productos',
                'user'          => $user,
                'organization'  => $organization,
                "products"      => $RProduct->findBy(
                    [
                        'sm_marca_id' => $organizationInfo[0]->getSmMarcaId(),
                        'issold' => 'Y',
                        'isactive' => 'Y'
                    ],
                    null
                ),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        
        return  $response;
    }

    /**
     * Ruta para ver el detalle de un producto
     * @Route("/producto/{codigo}", name="producto_view")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param string $codigo Codigo del producto
     *
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function view(Jaxon $jaxon, ManagerRegistry $manager, String $codigo): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $response = new Response();

        /** Session Variables */
        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);

        /** Entity Repository */
        $RProduct = new MProductRepository($manager);
        
        $response->setContent(
            $this->twig->render('modules/product/product.html', [
                'title'         => 'Pedidos Web | Producto | ' . strtoupper($codigo),
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Productos',
                'breadcrumb'    => $codigo,
                'user'          => $user,
                'organization'  => $organization,
                'product'       => $RProduct->findBy(
                    ['value' => $codigo],
                    null,
                    1
                )[0],
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );

        return $response;
    }
}

?>