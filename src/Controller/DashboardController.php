<?php

namespace App\Controller;

@session_start();

use App\Jaxon\Dashboard;
use App\Util;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends BaseController
{
    public function dashboard(Jaxon $jaxon)
    {
        if(Util::VerifySession()) return $this->logout();

        $response = new Response();
        $response->setContent(
            $this->twig->render('modules/dashboard.html', [
                'title'         => 'Pedidos Web | Dashboard',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Dashboard',
                'user'          => $_SESSION['user'],
                'org'           => $_SESSION['organization'],
                'stats'         => Dashboard::stats(),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
        
        return $response;
    }
}

?>