<?php

namespace App\Controller;

@session_start();

use App\Jaxon\Product;
use App\Model\Product as ModelProduct;
use App\Util;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ProductController extends AbstractController
{
    public $loader;

    public $targetDir;

    public $twig;
    
    public function __construct()
    {
        $this->targetDir = "/assets/img/product";

        $this->loader   = new FilesystemLoader('../templates');
        $this->twig     = new Environment($this->loader, [
            'cache'         => '../templates/cache',
            'autoescape'    => false,
            'debug'         => true
        ]);
    }

    public function add_image(String $codigo)
    {
        if ( Util::VerifySession() ) $this->logout();

        $image      = new ImageController($this->targetDir);
        $product    = new ModelProduct();

        $image->upload($_FILES, $codigo);

        if( $image->code ) 
            $product->add_image($codigo, $image->name, $this->targetDir);
        
        if( !$product->code ) 
            $image->delete($codigo . '/' . $image->name);

        $response = new Response(
            "{response: '". var_dump($product->code) ."', msj: '". $product->msj ."'}",
            $product->code ? Response::HTTP_ACCEPTED : Response::HTTP_NOT_ACCEPTABLE,
            ['Content-Type', 'application/json']
        );

        return $response;
    }

    public function list(Jaxon $jaxon)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/product/list.html', [
                'title'         => 'Pedidos Web | Productos',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Productos',
                'user'          => ( isset($_SESSION['user']) ) ? $_SESSION['user'] : null,
                'products'      => Product::showProducts(),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        
        return ( Util::VerifySession() ) ? $this->logout() : $response ;
    }

    public function logout(): RedirectResponse { return $this->redirectToRoute('ingresar'); }

    public function show(Jaxon $jaxon, String $codigo)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/product/product.html', [
                'title'         => 'Pedidos Web | Producto | ' . strtoupper($codigo),
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Productos',
                'breadcrumb'    => $codigo,
                'user'          => ( isset($_SESSION['user']) ) ? $_SESSION['user'] : null,
                'product'       => Product::getProduct($codigo),
                'images'        => Product::imagesPerProduct($codigo),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );

        return ( Util::VerifySession() ) ? $this->logout() : $response ;
    }
}

?>