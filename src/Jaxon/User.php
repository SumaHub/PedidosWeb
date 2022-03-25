<?php

namespace App\Jaxon;

use App\Entity\AdUser;
use App\Model\User as ModelUser;
use App\Repository\AdUserRepository;
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
     * @param string $email Correo del usuario
     * 
     * @return AdUser
     */
    public function exist(
        String $email
    )
    {
        $RUser = new AdUserRepository($this->manager);
        return $RUser->findBy(['email' => $email], null, 1);
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

        if(empty($formData['email']) && empty($formData['password']))
            return $jxnr->alert("Introduzca datos validos!");

        $user = $this->exist($formData['email']);
        
        if( empty($user) )
            return $jxnr->alert("Este usuario no se encuentra registrado");

        if( !$user[0]->getIsactive() || $user[0]->getIslocked() )
            return $jxnr->alert("El usuario esta inactivo o bloqueado");
        
        if( !self::auth($user[0], $formData['password']) )
            return $jxnr->alert("Las credenciales no coinciden");

        $session->set('user', $user[0]);
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

    /**
     * Obten usuarios por rol
     * 
     * @param int $AD_Rol_ID Identificador del rol
     * 
     * @return string|ADORecordSet Mensaje de error | Dato de los usuarios
     */
    public function getByRole(
        Int $AD_Role_ID = 0
    )
    {
        $user = new ModelUser;
        return $user->get_by_role($AD_Role_ID);
    }
}

?>