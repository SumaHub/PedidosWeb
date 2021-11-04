<?php

namespace App\Controller;

class DBController
{
    public static function conectar()
    {
        $db = ADONewConnection('postgres9');
        $db->setFetchMode(ADODB_FETCH_ASSOC);  
        $db->Connect('localhost', 'adempiere', 'adempiere', 'idempiere');
        return $db;
    }
}

?>