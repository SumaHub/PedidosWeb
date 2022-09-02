<?php

namespace App\Controller;

use App\Constant;
use App\Repository\Main\AdOrginfoRepository;
use App\Repository\Main\MPricelistRepository;
use App\Repository\Main\MProductCategoryRepository;
use App\Repository\Main\MProductRepository;
use App\Repository\Main\SmMarcaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
{

    public int $tableID = 208;

    public int $windowID = 140;

    /**
     * Ruta para ver todos los productos
     * @Route("/galeria", name="galeria")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function gallery(Jaxon $jaxon, ManagerRegistry $manager, Request $request): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $organization = $this->session->get('organization', null);
        $ROrg = new AdOrginfoRepository($manager);
        $orgInfo = $ROrg->findOneBy(['ad_org_id' => $organization->getAdOrgId()]);

        $RMarca = new SmMarcaRepository($manager);
        $marca = $RMarca->find($orgInfo->getSmMarcaId());
        $categoriesByMarca = $marca->getSmCategoriaProductoMarca();
        $categories = [];
        while ($category = $categoriesByMarca->next()) {
            array_push($categories, $category->getMProductCategoryId());
        }
        
        $actual     = $request->query->get('a', 1);
        $limit      = $request->query->get('l', 12);
        $offset     = $actual <= 1 ? 0 : $limit * ($actual - 1);
        $RProduct   = new MProductRepository($manager);
        $products   = $RProduct->findBy(
            [
                'sm_marca_id' => $orgInfo->getSmMarcaId(),
                'issold' => 'Y',
                'isactive' => 'Y'
            ],
            null,
            $limit,
            $offset
        );
        $productsQty = $RProduct->findBy(
            [
                'sm_marca_id' => $orgInfo->getSmMarcaId(),
                'issold' => 'Y',
                'isactive' => 'Y'
            ],
            null
        );
        $productsQty = count($productsQty);

        $user = $this->session->get('user', null);
        $RCategory = new MProductCategoryRepository($manager);
        return new Response(
            $this->twig->render('modules/product/gallery.html.twig', [
                'title'         => 'Pedidos Web | Galería',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Galería',
                'user'          => $user,
                'organization'  => $organization,
                'categories'    => $RCategory->findBy(
                    [
                        'm_product_category_id' => $categories,
                        'isactive' => 'Y'
                    ]
                ),
                'actual'        => $actual,
                'limit'         => $limit,
                'products'      => $products,
                'productsQty'   => $productsQty,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
    }

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

        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $organization = $this->session->get('organization', null);
        $ROrg = new AdOrginfoRepository($manager);
        $orgInfo = $ROrg->findOneBy(['ad_org_id' => $organization->getAdOrgId()]);

        $RMarca = new SmMarcaRepository($manager);
        $marca = $RMarca->find($orgInfo->getSmMarcaId());
        $categoriesByMarca = $marca->getSmCategoriaProductoMarca();
        $categories = [];
        while ($category = $categoriesByMarca->next()) {
            array_push($categories, $category->getMProductCategoryId());
        }
        

        $user = $this->session->get('user', null);
        $RCategory = new MProductCategoryRepository($manager);
        $RProduct = new MProductRepository($manager);
        return new Response(
            $this->twig->render('modules/product/list.html.twig', [
                'title'         => 'Pedidos Web | Productos',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Productos',
                'user'          => $user,
                'organization'  => $organization,
                'categories'    => $RCategory->findBy(
                    [
                        'm_product_category_id' => $categories,
                        'isactive' => 'Y'
                    ]
                ),
                'products'      => $RProduct->findBy(
                    [
                        'sm_marca_id' => $orgInfo->getSmMarcaId(),
                        'issold' => 'Y',
                        'isactive' => 'Y'
                    ],
                    null,
                    10
                ),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
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

        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $RProduct = new MProductRepository($manager);
        $product = $RProduct->findOneBy(['value' => $codigo]);

        $RPricelist = new MPricelistRepository($manager);

        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);
        return new Response(
            $this->twig->render('modules/product/view.html.twig', [
                'title'         => 'Pedidos Web | Producto | ' . strtoupper($codigo),
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Productos',
                'breadcrumb'    => $codigo,
                'user'          => $user,
                'organization'  => $organization,
                'product'       => $product,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
    }
}

?>