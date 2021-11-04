<?php

namespace App\Model;

use App\Controller\DBController;

class User
{
    /**
     * Obtiene los datos de un usuario
     * 
     * @param string $email Correo del usuario
     * 
     * @return string|array Mensaje de error | Datos del usuario
     */
    public function get_user(
        String $email
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $user = $db->GetRow(
                "SELECT au.AD_User_ID AS id, au.AD_User_ID, au.Email, au.Name, LOWER(au.Description) AS Description, au.Password, au.Salt, cb.IsSalesRep 
                FROM AD_User au 
                JOIN AD_User_Roles aur ON aur.AD_User_ID = au.AD_User_ID
                LEFT JOIN C_BPartner cb ON cb.C_BPartner_ID = au.C_BPartner_ID
                WHERE
                    (aur.AD_Role_ID = 1000170 OR aur.AD_Role_ID = 1000093) -- Solo Programadores y Vendedores
                    AND LOWER(TRIM(au.email)) = LOWER(TRIM('{$email}'))
                LIMIT 1"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $user ;
    }

    /**
     * Obten usuarios por rol
     * 
     * @param int $AD_Rol_ID Identificador del rol
     * 
     * @return string|ADORecordSet Mensaje de error | Dato de los usuarios
     */
    public function get_by_role(
        Int $AD_Role_ID = 0
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if($db->IsConnected()){
            $users = $db->Execute(
                "SELECT au.AD_User_ID AS id, au.AD_User_ID, au.Email, au.Name, LOWER(au.Description) AS Description, au.Password, au.Salt, cb.IsSalesRep 
                FROM AD_User au 
                LEFT JOIN C_BPartner cb ON cb.C_BPartner_ID = au.C_BPartner_ID
                JOIN AD_User_Roles aur ON aur.AD_User_ID = au.AD_User_ID
                WHERE au.IsActive = 'Y' AND au.Email IS NOT NULL 
                AND CASE WHEN $AD_Role_ID > 0 THEN aur.AD_Role_ID = $AD_Role_ID ELSE aur.AD_Role_ID > 0 END"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return $msj ?? $users ;
    }
}

?>