<?php

namespace App\Controller;

use App\Constant;
use App\Repository\Main\AdOrginfoRepository;
use App\Repository\Main\SmCharacteristicRepository;
use Doctrine\Persistence\ManagerRegistry;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacteristicController extends BaseController
{

    public int $tableID = 1000329;

    public int $windowID = 1000101;

    /**
     * Ruta para ver todos los caracteristicas
     * @Route("/caracteristicas", name="caracteristicas")
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
        
        $RCharacteristic = new SmCharacteristicRepository($manager);
        $user = $this->session->get('user', null);
        return new Response(
            $this->twig->render('modules/characteristic/list.html.twig', [
                'title'         => 'Pedidos Web | Caracteristicas',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Caracteristicas',
                'user'          => $user,
                'characteristics'   => $RCharacteristic->findAll(),
                'organization'  => $organization,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),
            ])
        );
    }

    /**
     * Ruta para ver el detalle de una caracteristica
     * @Route("/caracteristica/{id}", name="caracteristica_view")
     * 
     * @param \Jaxon\AjaxBundle\Jaxon $jaxon
     * @param \Doctrine\Persistence\ManagerRegistry $manager
     * @param string $id Identificador de la caracteristica
     *
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function view(Jaxon $jaxon, ManagerRegistry $manager, String $id): Response
    {
        if( !$this->VerifySession() ) 
            return $this->logout();

        $role = $this->session->get('role', null);
        if( !$this->VerifyWindow($manager, $this->windowID, $role->getId()) )
            return $this->login();

        $RCharacteristic = new SmCharacteristicRepository($manager);
        $characteristic = $RCharacteristic->find($id);

        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);
        return new Response(
            $this->twig->render('modules/characteristic/list.html.twig', [
                'title'         => 'Pedidos Web | Caracteristica | ',
                'version'       => 'Versi&oacute;n 2.0.0',
                'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                'modulo'        => 'Caracteristicas',
                'user'          => $user,
                'characteristic'=> $characteristic,
                'organization'  => $organization,
                'jaxonCss'      => $jaxon->css(),
                'jaxonJs'       => $jaxon->js(),
                'jaxonScript'   => $jaxon->script(),                
            ])
        );
    }
}
?>