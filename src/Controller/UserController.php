<?php

namespace App\Controller;

use App\Repository\AdUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    /**
     * Ruta para mostrar formulario de ingreso
     * @Route("/ingresar", name="ingresar")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon
     * 
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function signin(Jaxon $jaxon): Response
    {
        $response = new Response();
        $session = new Session();
        
        $session->set('logged', false);
        
        $response->setContent(
            $this->twig->render('modules/user/signin.html', [
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

    /**
     * Ruta para mostrar formulario para elegir
     * una organizacion
     * @Route("/organizacion", name="organizacion")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * 
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function organization(Jaxon $jaxon, ManagerRegistry $manager): Response
    {
        if( !$this->VerifySession() )
            return $this->logout();

        $response = new Response();

        $user = $this->session->get('user', null);
        $RUser = new AdUserRepository($manager);

        $response->setContent(
            $this->twig->render('modules/user/organization.html', [
                'title'         => 'Pedidos Web | Organizaci&oacute;n',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition login-page',
                'user'          => $user,
                'roles'         => $RUser->findRoles($user->getId()),
                'organizations' => $RUser->findOrgs($user->getId()),
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script()
            ])
        );

        return $response;
    }
}

?>