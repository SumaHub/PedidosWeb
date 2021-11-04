<?php

namespace App\Jaxon;

use App\Model\BPartner as ModelBPartner;
use App\Model\Order as ModelOrder;
use Jaxon\Response\Response;

class BPartner extends Base
{
    public function getBPartners(String $code = '', Int $user = 0)
    {
        $jxnr       = new Response;
        $model      = new ModelBPartner;
        $bpartners  = $model->get_bpartner($code, $_SESSION['user']['issalesrep'] == 'Y' ? $_SESSION['user']['ad_user_id'] : $user);
        $html       = '<table id="tablaCliente" class="table"><thead><tr><th>Nombre</th><th>C&oacute;digo</th></tr></thead><tbody>';
        if($bpartners->numRows() > 0) {
            while ($bpartner = $bpartners->fetchRow()) {
                $html .= '<tr style="cursor: pointer;" onclick="App.Jaxon.BPartner.setBPartner(\''. $bpartner['codigo'] .'\')"><td>'.$bpartner['nombre'].'</td><td>'.$bpartner['codigo'].'</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">NO SE HA ENCONTRADO NINGUN CLIENTE</td></tr>';
        }
        $html       .= '</tbody></table>';
        $jxnr
            ->assign('clientes', 'innerHTML', $html)
            ->script('$("#tablaCliente").dataTable({ columnDefs: [{ orderable: true, targets: 0 }], order: [[1, "asc" ]], responsive: true, lengthChange: false, autoWidth: false, dom: \'<"row"<"col-sm-12 col-md-12"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>\', buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"] })');
        return $jxnr;
    }

    public function getPhone(Int $location = null)
    {
        $jxnr       = new Response;
        $model      = new ModelBPartner;

        if ($location) 
            $jxnr->assign('telefono', 'value', $model->get_phone($location));
        else
            $jxnr->alert('No olvides seleccionar una direcion!');

        return $jxnr;
    }

    public function setBPartner(String $code = '', Int $user = 0)
    {
        $jxnr       = new Response;
        $model      = new ModelBPartner;
        $order      = new ModelOrder;

        if(empty($code))
            return $jxnr->script('Swal.fire("Oops!", "El campo no puede estar vacio!", "warning")');
        
        // Obtiene los datos del cliente
        $bpartner   = $model->get_bpartner($code, $_SESSION['user']['issalesrep'] == 'Y' ? $_SESSION['user']['ad_user_id'] : $user);
        $data       = $bpartner->fetchRow();
        $locations  = $model->get_location($data['codigo']);

        $address = '';
        $i = 0;
        while ($location = $locations->fetchRow()) {
            // Seleccionar por defecto los datos de la primera localizacion del tercero
            if($i == 0) $phone = $model->get_phone($location['c_bpartner_location_id']);
            $address .= '<option value="'.$location['c_bpartner_location_id'].'">'.$location['address1'].'</option>';
            $i++;
        }

        // Cambia el cliente en la Orden
        if ( isset($_SESSION['order']['sm_order_id']) && !$order->change_bpartner($data['c_bpartner_id']) )
            $jxnr->script( self::displayError('Oops!', 'No fue posible cambiar el cliente del pedido.') );
        else
            $jxnr
                ->assign('tercero_id', 'value', $data['c_bpartner_id'])
                ->assign('tercero', 'value', $data['codigo'])
                ->assign('descripcion', 'value', $data['nombre'])
                ->assign('direccion', 'innerHTML', $address)
                ->assign('telefono', 'value', $phone)
                ->assign('direccion', 'disabled', false);

        return $jxnr;
    }
}

?>