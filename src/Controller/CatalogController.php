<?php

namespace App\Controller;

use App\Constant;
use App\Repository\Main\AdOrginfoRepository;
use App\Repository\Main\MProductRepository;
use App\Repository\Main\SmMarcaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;

class CatalogController extends BaseController
{
    public function __construct()
    {
        parent::__construct(null);
        
        // PDF options
        $this->pdf      = new Pdf('/usr/local/bin/wkhtmltopdf', [
            'page-width'    => '210',  
            'page-height'   => '297',  
            'margin-bottom' => '0',
            'margin-left'   => '0',
            'margin-top'    => '0',
            'margin-right'  => '0',
            'orientation'   => 'Portrait',
            'disable-smart-shrinking' => true,
            'enable-local-file-access' => true,
            'print-media-type' => true
        ]);
        $this->pdf->setTemporaryFolder(dirname(__DIR__) . '/../public/tmp/');

        // TWIG options
        $this->twig     = new Environment($this->loader, [
            'cache'         => false,
            'autoescape'    => false,
            'debug'         => false
        ]);
        $this->twig->addExtension(new IntlExtension());
    }

    /**
     * Ruta para ver catalogo
     * @Route("/catalogo", "catalog")
     * 
     * @param Jaxon $jaxon
     * @param ManagerRegistry $manager
     * 
     * @return Response Vista
     */
    public function view(Jaxon $jaxon, ManagerRegistry $manager): Response
    {
        if( !$this->VerifySession() )
            return $this->logout();
            
        $organization = $this->session->get('organization');
        $ROrg = new AdOrginfoRepository($manager);
        $orgInfo = $ROrg->findOneBy(['ad_org_id' => $organization->getAdOrgId()]);

        $RMarca = new SmMarcaRepository($manager);
        $marca = $RMarca->find($orgInfo->getSmMarcaId());
        $categoriesByMarca = $marca->getSmCategoriaProductoMarca();
        
        $products = [];
        $RProduct = new MProductRepository($manager);
        while ($category = $categoriesByMarca->next()) {
            $categoryID = $category->getMProductCategoryId();
            $products[$categoryID] = $RProduct->findToCatalog( $orgInfo->getSmMarcaId(), $categoryID );
        }

        $user = $this->session->get('user');
        return  new Response(
            $this->twig->render('modules/catalog/view.html.twig', [
                'title'         => 'Pedidos Web | Catálogo',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Catálogo',
                'user'          => $user,
                'categories'    => $categoriesByMarca,
                'products'      => $products,
                'organization'  => $organization,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
    }

    /**
     * Ruta para ver catalogo
     * @Route("/dw/catalogo", "catalog")
     * 
     * @param Jaxon $jaxon
     * @param ManagerRegistry $manager
     * 
     * @return Response Vista
     */
    public function download(Jaxon $jaxon, ManagerRegistry $manager)
    {
        if( !$this->VerifySession() )
            return $this->logout();
            
        $organization = $this->session->get('organization');
        $ROrg = new AdOrginfoRepository($manager);
        $orgInfo = $ROrg->findOneBy(['ad_org_id' => $organization->getAdOrgId()]);

        $RMarca = new SmMarcaRepository($manager);
        $marca = $RMarca->find($orgInfo->getSmMarcaId());
        $categoriesByMarca = $marca->getSmCategoriaProductoMarca();
        
        $products = [];
        $RProduct = new MProductRepository($manager);
        while ($category = $categoriesByMarca->next()) {
            $categoryID = $category->getMProductCategoryId();
            $products[$categoryID] = $RProduct->findToCatalog( $orgInfo->getSmMarcaId(), $categoryID );
        }

        $user = $this->session->get('user');
        $html = $this->twig->render('modules/catalog/download.html.twig', [
            'title'         => 'Pedidos Web | Catálogo',
            'version'       => 'Versi&oacute;n 2.0.0',
            'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
            'modulo'        => 'Catálogo',
            'home_path'     => Constant::HOME_PATH,
            'user'          => $user,
            'categories'    => $categoriesByMarca,
            'products'      => $products,
            'organization'  => $organization
        ]);
        $document = $this->pdf->getOutputFromHtml($html);
        return new PdfResponse($document, sprintf('CATALOGO_%s_%d.pdf', $marca->getName(), date('YmdHis')));
    }
}

?>