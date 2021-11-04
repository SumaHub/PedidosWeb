<?php

namespace App\Controller;

@session_start();

use App\Jaxon\Organization;
use App\Util;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    public function admin(Jaxon $jaxon)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/user/admin.html', [
                'title'         => 'Pedidos Web | Usuario',
                'version'       => 'Versi&oacute;n 2.0',
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        return $response;
    }

    public function dashboard(): RedirectResponse { return $this->redirectToRoute('dashboard'); }

    public function index(Jaxon $jaxon){ return ( Util::VerifySession() ) ? $this->login($jaxon) :  $this->dashboard(); }

    public function login(Jaxon $jaxon)
    {
        @session_destroy(); 
        session_start();
        $_SESSION['logged'] = false;
        
        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/user/login.html', [
                'title'         => 'Pedidos Web | Ingreso',
                'version'       => 'Versi&oacute;n 2.0',
                'bodyClass'     => 'hold-transition login-page',
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        return $response;
    }

    public function logout(): RedirectResponse { return $this->redirectToRoute('ingresar'); }

    public function organization(Jaxon $jaxon)
    {
        if(Util::VerifySession()) return $this->logout();

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/user/organization.html', [
                'title'         => 'Pedidos Web | Organizaci&oacute;n',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition login-page',
                'user'          => $_SESSION['user'],
                'organizations' => Organization::getOrganizations(),
                'warehouses'    => Organization::setWarehouse(),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>