<?php

namespace App\Jaxon;

use App\Model\User as ModelUser;
use App\Util;
use Jaxon\Response\Response;

class User extends Base
{
    public $data;
    
    /**
     * Compara la clave suministrada con los datos del usuario
     * 
     * @param array $user Datos del usuario
     * @param string $password Clave para encriptar
     * 
     * @return bool 
     */
    public static function auth(
        Array $user, 
        String $password
    )
    {
        $c1     = hash("sha512", Util::Hex2String($user['salt']) . $password, true);

        for($i = 0; $i < 1000; $i++){ $c1 = hash("sha512", $c1, true); }
        return Util::String2Hex($c1) == $user['password'] ? true : false;
    }

    /**
     * Corrobora si el usuario se encuentra registrado en la BD
     * 
     * @param string $email Correo del usuario
     * 
     * @return bool
     */
    public function exist(
        String $email
    )
    {
        $model      = new ModelUser;
        $this->data = $model->get_user($email);
        return (is_array($this->data)) ? true : false ;
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
     * @return object Jaxon\Response\Response
     */
    public function login(
        Array $formData = null
    )
    {
        $jxnr = new Response;

        if($formData['email'] != '' && $formData['password'] != ''){

            if( $this->exist($formData['email']) ){
            
                if( self::auth($this->data, $formData['password']) ){
                    $_SESSION['user'] = $this->data;
                    $_SESSION['user']['remember'] = (isset($formData['remember']) && $formData['remember'] == "on") ? true : false ;
                    $jxnr->redirect("/organizacion");
                } else {
                    $jxnr->alert("Las credenciales no coinciden");
                }

            } else {
                $jxnr->alert("Este usuario no se encuentra registrado");
            }

        } else {
            $jxnr->alert("Introduzca datos validos!");
        }

        return $jxnr;
    }

    /**
     * Destruye las variables de session del usuario
     * 
     * @return object Jaxon\Response\Response
     */
    public function logout()
    {
        @session_destroy();
        $jxnr = new Response;
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