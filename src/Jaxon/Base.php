<?php

namespace App\Jaxon;

use App\Util;
use Jaxon\Response\Response;

class Base extends \Jaxon\CallableClass
{
    public $actionList = [
        "Order"     =>  [
            "create"    => [
                // Ask Swal
                "title"             => "Deseas crear este pedido?",
                "text"              => "Al crear este pedido se enviara a la bandeja de aprobacion",
                "icon"              => "info",
                "confirmButtonText" => "Crear Pedido",
                "confirmButtonColor"=> "#28a745",
                "cancelButtonColor" => "#d33",

                // Result Swal
                "success"   => "Pedido Creado!",
                "error"     => "Ha ocurrido un error!"
            ],
            "delete"    => [
                // Ask Swal
                "title"             => "Deseas borrar este pedido?",
                "text"              => "Los datos del pedido no podran ser recuperados posteriormente.",
                "icon"              => "warning",
                "confirmButtonText" => "Borrar!",
                "confirmButtonColor"=> "#d33",
                "cancelButtonColor" => "#2f2f2f",

                // Result Swal
                "success"   => "Pedido Borrado!",
                "error"     => "Ha ocurrido un error!"
            ]
        ],
        "OrderLine" => [
            "delete" => [
                // Ask Swal
                "title"             => "Deseas borrar esta linea?",
                "text"              => "La linea de la orden sera eliminada.",
                "icon"              => "warning",
                "confirmButtonText" => "Borrar!",
                "confirmButtonColor"=> "#d33",
                "cancelButtonColor" => "#2f2f2f",
    
                // Result Swal
                "success"   => "Linea Borrada!",
                "error"     => "Ha ocurrido un error!"
            ]
        ]
    ];

    public function actionConfirm(
        String $controller,
        String $action,
        Int $id
    )
    {
        $jxnr = new Response;
        if ( array_key_exists($action, $this->actionList[$controller]) ) {
            $data = $this->actionList[$controller][$action];
            $jxnr->script(
                "Swal.fire({
                    title: '". $data['title'] ."',
                    text: '". $data['text'] ."',
                    icon: '". $data['icon'] ."',
                    showCancelButton: true,
                    confirmButtonText: '". $data['confirmButtonText'] ."',
                    confirmButtonColor: '". $data['confirmButtonColor'] ."',
                    cancelButtonText: `Cancelar`,
                    cancelButtonColor: '". $data['cancelButtonColor'] ."',
                }).then((result) => {
                    if (result.isConfirmed) {
                        App.Jaxon.". $controller .".". $action ."(". $id .");
                    }
                  })"
            );
        } else {
            $jxnr->script( self::displayError('Oops!', 'Esta acción no esta permitida') );
        }
        return $jxnr;
    }

    /**
     * Construye respuesta html de alerta SweetAlert2
     * para manejar los errores
     * 
     * @param string $title Titulo de la alerta
     * @param string $text Texto descriptivo
     * @param string $detail Detalles del error
     * 
     * @return string Respuesta HTML
     */
    public static function displayError(
        String $title,
        String $text,
        $detail = ''
    )
    {
        $detail = !empty($detail) ? "footer: '<a href=\"". Util::WriteLog($detail) ."\">M&aacute;s detalles...</a>'" : "footer: '<a href=\"#\">Lo siento, no tenemos mayor detalle...</a>'";
        $swal = 
            "Swal.fire({
                icon: 'error',
                title: '". $title ."', 
                text:  '". $text ."',
                ". $detail ."
            })";
        return $swal;
    }
}

?>