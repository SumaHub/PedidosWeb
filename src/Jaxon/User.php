<?php

namespace App\Jaxon;

use App\Entity\Main\AdUser;
use App\Repository\Main\AdUserRepository;
use App\Util;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class User extends Base
{    
    /**
     * Compara la clave suministrada con los datos del usuario
     * 
     * @param AdUser $user Datos del usuario
     * @param string $password Clave para encriptar
     * 
     * @return bool 
     */
    public static function auth(
        AdUser $user, 
        String $password
    ): bool
    {
        $password = Util::Encode($user->getSalt(), $password );
        return Util::String2Hex($password) == $user->getPassword() ? true : false;
    }

    /**
     * Corrobora si el usuario se encuentra registrado en la BD
     * 
     * @param string $name Nombre del usuario
     * 
     * @return AdUser
     */
    public function exist(
        String $name
    )
    {
        $RUser = new AdUserRepository($this->manager);
        return $RUser->findOneBy(['name' => $name]);
    }

    public function init()
    {
        $jxnr = new Response;
        $jxnr->setEvent('submit', 'onclick', rq('App.Jaxon.User')->call('login', pm()->form('login')));
        return $jxnr;
    }

    /**
     * Revisa los datos suministrado por el formulario 
     * al ingresar al sistema
     * 
     * @param array $formData Datos del usuario
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function login(
        Array $formData = null
    ): Response
    {
        $jxnr = new Response;
        $session = new Session();

        if(empty($formData['name']) && empty($formData['password']))
            return $jxnr->alert("Introduzca datos validos!");

        $user = $this->exist($formData['name']);
        
        if( empty($user) )
            return $jxnr->alert("Este usuario no se encuentra registrado");

        if( !$user->getIsactive() || $user->getIslocked() )
            return $jxnr->alert("El usuario esta inactivo o bloqueado");
        
        if( !self::auth($user, $formData['password']) )
            return $jxnr->alert("Las credenciales no coinciden");

        if( is_null($user->getCBpartner()) || !$user->getCBpartner()->getIssalesrep() )
            return $jxnr->alert("Lo sentimos, esta plataforma es solo para vendedores");

        $session->set('user', $user);
        $jxnr->redirect("/organizacion");

        return $jxnr;
    }

    /**
     * Destruye las variables de session del usuario
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function logout(): Response
    {
        $jxnr = new Response;
        $session = new Session();

        $session->invalidate();
        $jxnr->redirect('/');
        return $jxnr;
    }
}

?>