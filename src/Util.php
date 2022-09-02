<?php

namespace App;

use Symfony\Component\HttpFoundation\Session\Session;

class Util
{
    /**
     * Codifica la clave con el mismo proceso que iDempiere
     * @param string $salt Cadena de codificacion
     * @param string $clave Clave secreta de usuario
     * @return string $seed Clave convertia
     */
    public static function Encode(String $salt, String $password){

        $seed = hash("sha512", self::Hex2String($salt) . $password, true);
        for($i = 0; $i < 1000; $i++){ 
            $seed = hash("sha512", $seed, true); 
        }
        return $seed;
    }

    /** 
     * Convertir datos binarios en valores hexadecimales
     * @param string $string
     * @return string
     */
    public static function String2Hex($string){ return bin2hex($string); }
     
    /** 
     * Convertir una valores hexadecimales en datos binarios
     * @param string $string
     * @return string
     */
    public static function Hex2String($hex){ return hex2bin($hex); }

    public static function WriteLog(String $text)
    {
        $fileName   = 'error_' . date('YmdHis') . rand(1, intval(date('Hisu'))) . '.txt';
        $fileDir = dirname(__DIR__) . '/public/tmp/' . $fileName;
        $tempFile   = fopen($fileDir, 'w');
        fwrite($tempFile, $text);
        fclose($tempFile);
        return '/tmp/' . $fileName;
    }
}
