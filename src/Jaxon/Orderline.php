<?php

namespace App\Jaxon;

use App\Constant;
use App\Entity\COrderline;
use App\Model\OrderLine as ModelOrderLine;
use App\Repository\AdSequenceRepository;
use App\Repository\COrderlineRepository;
use App\Repository\CTaxRepository;
use App\Repository\MProductpriceRepository;
use App\Repository\MProductRepository;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Orderline extends Base
{
    /**
     * Habilitar campo para cambiar cantidades
     * de una linea en la orden
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function changeQty(
        Int $SM_Orderline_ID
    )
    {
        $jxnr       = new Response;
        $orderline  = new ModelOrderLine; 
        $input      = 
            '<input 
                type="number"
                step="0.01"
                value="'. number_format($orderline->get_qty($SM_Orderline_ID), 2) .'"
                onblur="App.Jaxon.Orderline.setQty(this.parentElement.parentElement.dataset.line, this.value)"
            >';
        return $jxnr->assign(base64_encode($SM_Orderline_ID . 'qty'), 'innerHTML', $input);
    }

    /**
     * Crear linea en la orden
     * 
     * @param int $m_productprice_id Identificacdor del precio del producto
     * @param array $formData Datos de la orden
     * 
     * @return string/array Mensaje de error / Datos de la linea
     */
    public function create(
        Int $m_productprice_id,
        Array $formData
    ): Response
    {
        $jxnr   = new Response;

        /** Session */
        $user = $this->session->get('user', null);
        $order = $this->session->get('order', null);
        $organization = $this->session->get('organization', null);
        $warehouse = $this->session->get('warehouse', null);
        
        /** Entidades */
        if( is_null($order) ){
            $JOrder = new Order($this->manager);
            $order = $JOrder->create($formData);
            $jxnr->assign('documentno', 'value', $order->getDocumentno());
        }
        
        $RProductprice = new MProductpriceRepository($this->manager);
        $productprice = $RProductprice->find($m_productprice_id);

        $RProduct = new MProductRepository($this->manager);
        $product = $RProduct->find($productprice->getMProductId());
        
        // Verifica si el producto tiene stock
        if( $RProduct->findStorage($product->getId(), $warehouse->getId()) ) 
            return $jxnr->script( self::displayError("Producto sin Stock", "Este producto no cuenta con cantidades disponibles.") );

        $ROrderline = new COrderlineRepository($this->manager);
        // Verifica si el producto ya esta registrado en la orden
        if( count( $ROrderline->findBy(['c_order_id' => $order->getId(), 'm_product_id' => $product->getId()]) ) > 0 )
            return $jxnr->script( self::displayError("Producto Repetido", "Este producto se encuentra registrado, modifique la cantidad de ser necesario.") );

        
        $RSequence = new AdSequenceRepository($this->manager);
        $ROrderline = new COrderlineRepository($this->manager);
        $orderline = new COrderline();
        $orderline
            ->setAdClientId(Constant::AD_Client_ID)
            ->setAdOrgId($order->getAdOrgId())
            ->setCActivityId(Constant::C_Activity_ID)
            ->setCBpartnerId($order->getCBpartnerId())
            ->setCBpartnerLocationId($order->getCBpartnerLocationId())
            ->setCOrderId($order->getId())
            ->setCOrderlineId( $RSequence->findNextSequence($ROrderline->sequence) )
            ->setCUomId($product->getCUomId())
            ->setLinenetamt($productprice->getPricelist())
            ->setMProductId($productprice->getMProductId())
            ->setMWarehouseId($order->getMWarehouseId())
            ->setPriceactual($productprice->getPricelist())
            ->setPriceentered($productprice->getPricelist())
            ->setPricelist($productprice->getPricelist())
            ->setQtyentered(1)
            ->setQtyordered(1)
            ->setQtyreserved(1)
            ->setCOrderlineUu( $RSequence->findNextUU() );

        $RTax = new CTaxRepository($this->manager);
        $tax = $RTax->findBy([
            'ad_client_id' => Constant::AD_Client_ID,
            'c_taxcategory_id' => $product->getCTaxcategoryId(),
            'sopotype' => ['S', 'B'],
            'isdefault' => 'Y',
            'isactive' => 'Y'
        ]);
        $orderline->setCTaxId($tax[0]->getId());
        
        $manager = $this->manager->getManagerForClass(COrderline::class);
        $manager->persist($orderline);
        $manager->flush();

        # TODO: Actualizar monto de la orden

        // Crear respuesta HTML
        $html = 
        '<tr id="'. base64_encode($orderline->getId()) .'" class="text-center orderline" data-line="'. $orderline->getId() .'">
            <td class="text-center">'. $orderline->getLine() .'</td>
            <td class="text-left">'. $orderline->getMProduct()->getName() .'</td>
            <td class="text-center">'. $orderline->getMProduct()->getValue() .'</td>
            <td class="text-right">'. number_format($orderline->getPriceactual(), 2) .'</td>
            <td id="'. base64_encode($orderline->getId() . "qty") .'" class="text-right qty">
                <p onclick="App.Jaxon.Orderline.changeQty(this.parentElement.parentElement.dataset.line)">'. number_format($orderline->getQtyordered(), 2) .'</p>
            </td>
            <td id="'. base64_encode($orderline->getId() . "amt") .'" class="text-right">
                '. number_format($orderline->getLinenetamt(), 2) .'
            </td>
            <td class="btn-action">
                <i onclick="App.Jaxon.Base.actionConfirm(\'deleteOrderline\', \'Orderline\', this.parentElement.parentElement.dataset.line)" class="fas fa-trash text-danger"></i>
            </td>
        </tr>';
        $jxnr
            ->prepend('tablaProductos', 'innerHTML', $html)
            ->assign('totallines', 'value', number_format($order->getTotallines, 2))
            ->assign('grandtotal', 'value', number_format($order->getGrandtotal, 2));

        return  $jxnr;
    }

    /**
     * Borra una linea de una orden temporal
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function delete(
        Int $SM_Orderline_ID
    )
    {
        $jxnr       = new Response;
        $orderline  = new ModelOrderLine;
        $result     = $orderline->delete($SM_Orderline_ID);

        if ( is_object($result) ) {
            // Actualizar montos de la Orden
            Order::syncAmt($_SESSION['order']['sm_order_id']);

            // Actualizar numeros lineas
            Order::syncLines($_SESSION['order']['sm_order_id']);

            // Actualizar variable de las lineas
            $_SESSION['order']['orderlines'] = self::get($_SESSION['order']['sm_order_id']);

            // Quitar renglon
            $jxnr
                ->remove(base64_encode($SM_Orderline_ID))
                ->assign('totallines', 'value', number_format($_SESSION['order']['totallines'], 2))
                ->assign('taxamt', 'value', number_format($_SESSION['order']['grandtotal'] - $_SESSION['order']['totallines'], 2))
                ->assign('grandtotal', 'value', number_format($_SESSION['order']['grandtotal'], 2));

            foreach ($_SESSION['order']['orderlines'] as $orderline) {
                $jxnr->assign(base64_encode($orderline['sm_orderline_id'] . 'line'), 'innerHTML', $orderline['line']);
            }    
        } else {
            $jxnr->script( self::displayError('Oops!', 'No fue posible borrar la linea de esta orden', json_encode( $result )) );
        }

        return $jxnr;
    }

    public static function get(
        Int $SM_Order_ID,
        Bool $IsTemp = true
    )
    {
        $orderline = new ModelOrderLine;
        return $orderline->get($SM_Order_ID, $IsTemp);
    }

    /**
     * Lista los productos coincidentes
     * 
     * @param string $value Codigo o Nombre del producto
     * @param array $formData Datos del formulario
     * 
     * @return \Jaxon\Response\Response Respuesta
     */
    public function getProducts(
        String $value = '', 
        Array $formData
    ): Response
    {
        $jxnr   = new Response;
        $session = new Session();

        /** Session */
        $user = $session->get('user', null);
        $organization = $session->get('organization', null);
        $warehouse = $session->get('warehouse', null);
        
        /** Repositorios */
        $RProduct = new MProductRepository($this->manager);
        $RProductprice = new MProductpriceRepository($this->manager);

        // La organizacion debe ser seleccionada para listar los productos
        if(!isset($organization) || $formData['organization'] == 0)
            return $jxnr->script('Swal.fire("Oops!", "Primero debe seleccionar una organizaci&oacute;n!", "warning")');

        // La lista de precio debe ser seleccionada para mostrar los precios de los articulos
        if(!isset($formData['pricelist']) || $formData['pricelist'] == 0)
            return $jxnr->script('Swal.fire("Oops!", "No has seleccionado una lista de precio!", "warning")');

        /** Datos */
        $pps = $RProductprice->findProductPrices($formData['organization'], $formData['pricelist'], $value); 

        $html= '<table id="productTable" class="table"><thead><tr><th>Marca</th><th>Nombre</th><th>C&oacute;digo</th><th>Cantidad</th><th>Precio</th></tr></thead><tbody>';
        if( count($pps) > 0) {
            foreach ($pps as $pp) {
                $html .= 
                '<tr style="font-size: 0.9rem; cursor: pointer;" onclick="App.Jaxon.Orderline.create(\''. $pp->getId() .'\', jaxon.getFormValues(\'order\'))">
                    <td>'.$pp->getMProduct()->getSmMarca()->getName().'</td>
                    <td>'.$pp->getMProduct()->getName().'</td>
                    <td>'.$pp->getMProduct()->getValue().'</td>
                    <td>'.$RProduct->findStorage($pp->getMProductId(), $warehouse->getId()) .'</td>
                    <td>$'.$pp->getPricestd().'</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">NO SE HA ENCONTRADO NINGUN PRODUCTO</td></tr>';
        }
        $html       .= '</tbody></table>';
        
        $jxnr
            ->assign('products', 'innerHTML', $html)
            ->script('$("#productTable").dataTable({ columnDefs: [{ orderable: true, targets: 0 }], order: [[1, "asc" ]], responsive: true, lengthChange: false, autoWidth: false, dom: \'<"row"<"col-sm-12 col-md-12"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>\', buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"] })');

        return $jxnr;
    }

    /**
     * Cambiar cantidad de un producto en una linea 
     * 
     * @param int $SM_Orderline_ID Identificador de la linea
     * @param string $qty Cantidad
     * 
     * @return object Jaxon\Response\Response
     */
    public function setQty(
        Int $SM_Orderline_ID,
        String $qty
    )
    {
        $jxnr       = new Response;
        $orderline  = new ModelOrderLine; 
        
        $data = $orderline->set_qty($SM_Orderline_ID, $qty);
        if( is_array($data) ) {
            // Actualizar montos
            Order::syncAmt($_SESSION['order']['sm_order_id']);

            // Actualizar campos
            $jxnr
                ->assign(base64_encode($SM_Orderline_ID . 'qty'), 'innerHTML', '<p onclick="App.Jaxon.Orderline.changeQty(this.parentElement.parentElement.dataset.line)">'. number_format($qty, 2) .'</p>')
                ->assign(base64_encode($SM_Orderline_ID . 'amt'), 'innerHTML', number_format($data['linenetamt'], 2))
                ->assign('totallines', 'value', number_format($_SESSION['order']['totallines'], 2))
                ->assign('taxamt', 'value', number_format($_SESSION['order']['grandtotal'] - $_SESSION['order']['totallines'], 2))
                ->assign('grandtotal', 'value', number_format($_SESSION['order']['grandtotal'], 2));
        } else {
            $jxnr->script(self::displayError("Oops!", "Ha fallado el guardado automatico de la Linea", $data) );
        }

        return $jxnr;
    }
}

?>