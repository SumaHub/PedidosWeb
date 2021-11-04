<?php

namespace App;

class Util
{
    /** 
     * String2Hex
     * Convertir datos binarios en valores hexadecimales
     * @param string $string
     * @return string
     */
    public static function String2Hex($string){ return bin2hex($string); }
     
    /** 
     * Hex2String
     * Convertir una valores hexadecimales en datos binarios
     * @param string $string
     * @return string
     */
    public static function Hex2String($hex){ return hex2bin($hex); }

    /** 
     * GetCache
     * Retorna el estatus de la cache de los exploradores
     * @param string $string
     * @return boolean
     */
    public static function GetCache(){ return ( isset($_SERVER['HTTP_CACHE_CONTROL']) ) ? $_SERVER['HTTP_CACHE_CONTROL'] : "no-cache"; }

    /** 
     * VerifySession
     * Verifica las variables de sesion y la memoria cache del cliente
     * @param string $string
     * @return boolean
     */
    public static function VerifySession(){
        return ( !isset($_SESSION['user']) ) ? true : false;
    }

    public static function WriteLog(String $text)
    {
        $fileName   = 'error_' . date('YmdHis') . rand(1, intval(date('Hisu'))) . '.txt';
        $fileDir = dirname(__DIR__) . '/public/temp/' . $fileName;
        $tempFile   = fopen($fileDir, 'w');
        fwrite($tempFile, $text);
        fclose($tempFile);
        return '/temp/' . $fileName;
    }
}
