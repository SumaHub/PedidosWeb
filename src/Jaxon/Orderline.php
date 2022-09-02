<?php

namespace App\Jaxon;

use App\Constant;
use App\Entity\COrderline;
use App\Repository\AdSequenceRepository;
use App\Repository\COrderlineRepository;
use App\Repository\COrderRepository;
use App\Repository\CTaxRepository;
use App\Repository\MProductpriceRepository;
use App\Repository\MProductRepository;
use Exception;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Orderline extends Base
{
    /**
     * Habilitar campo para cambiar cantidades
     * de una linea en la orden
     * 
     * @param string $C_Orderline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function changeQty(
        String $C_Orderline_ID
    )
    {
        $C_Orderline_ID = base64_decode($C_Orderline_ID);
        $ROrderline = new COrderlineRepository($this->manager);
        $orderline  = $ROrderline->find( $C_Orderline_ID );
        $qty = $orderline->getQtyentered();

        $jxnr = new Response;
        $input= 
            '<input 
                type="number"
                step="0.01"
                value="'. number_format($qty, 2) .'"
                onblur="App.Jaxon.Orderline.setQty(\''. base64_encode($C_Orderline_ID) .'\', this.value)"
            >';
        return $jxnr->assign(base64_encode($C_Orderline_ID . 'qty'), 'innerHTML', $input);
    }

    /**
     * Habilitar campo para cambiar precios
     * de una linea en la orden
     * 
     * @param string $C_Orderline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function changePrc(
        String $C_Orderline_ID
    )
    {
        $C_Orderline_ID = base64_decode($C_Orderline_ID);
        $ROrderline = new COrderlineRepository($this->manager);
        $orderline  = $ROrderline->find( $C_Orderline_ID );
        $prc = $orderline->getPriceactual();

        $jxnr = new Response;
        $input= 
            '<input 
                type="number"
                step="0.01"
                value="'. number_format($prc, 2) .'"
                onblur="App.Jaxon.Orderline.setPrc(\''. base64_encode($C_Orderline_ID) .'\', this.value)"
            >';
        return $jxnr->assign(base64_encode($C_Orderline_ID . 'prc'), 'innerHTML', $input);
    }

    /**
     * Crear linea en la orden
     * #TODO: Cambiar monto de la orden 
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

            $buttons = 
                '<div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <button id="cancel" class="mx-1 btn btn-lg btn-danger"><i class="fas fa-cog pr-2"></i>Anular</button>
                                    <button id="process" class="mx-1 btn btn-lg btn-success"><i class="fas fa-cog pr-2"></i>Preparar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';

            $jxnr
                ->assign('documentno', 'value', $order->getDocumentno())
                ->append('contenedor', 'innerHTML', $buttons)
                ->setEvent('process', 'onclick', rq('App.Jaxon.Order')->call('process'), base64_encode($order->getId()) );
        }
        
        $RProductprice = new MProductpriceRepository($this->manager);
        $productprice = $RProductprice->find($m_productprice_id);

        $RProduct = new MProductRepository($this->manager);
        $product = $RProduct->find($productprice->getMProductId());
        
        // Verifica si el producto tiene stock
        if( $RProduct->findStorage($product->getId(), $warehouse->getId()) <= 0 ) 
            return $jxnr->script( self::displayError("Producto sin Stock", "Este producto no cuenta con cantidades disponibles.") );

        // Verifica si el producto ya esta registrado en la orden
        $ROrderline = new COrderlineRepository($this->manager);
        if( count( $ROrderline->findBy(['c_order_id' => $order->getId(), 'm_product_id' => $product->getId(), 'isactive' => 'Y']) ) > 0 )
            return $jxnr->script( self::displayError("Producto Repetido", "Este producto se encuentra registrado, modifique la cantidad de ser necesario.") );

        $orderlines = $ROrderline->findBy(['c_order_id' => $order->getId()]);
        
        $RSequence = new AdSequenceRepository($this->manager);
        $Orderline = new COrderline();
        $Orderline
            ->setAdClientId( Constant::AD_Client_ID )
            ->setAdOrgId( $order->getAdOrgId() )
            ->setCActivityId( Constant::C_Activity_ID )
            ->setCBpartnerId( $order->getCBpartner()->getId() )
            ->setCBpartnerLocationId( $order->getCBpartnerLocation()->getId() )
            ->setCCurrencyId( $order->getCCurrency()->getId() )
            ->setCOrderId( $order->getId() )
            ->setCOrderlineId( $RSequence->findNextSequence( $ROrderline->sequence ) )
            ->setCUomId( $product->getCUomId() )
            ->setLine( (count($orderlines) + 1) * 10 )
            ->setLinenetamt( $productprice->getPricelist() )
            ->setMProduct( $product )
            ->setMWarehouseId( $order->getMWarehouseId() )
            ->setPriceactual( $productprice->getPricelist() )
            ->setPriceentered( $productprice->getPricelist() )
            ->setPricelist( $productprice->getPricelist() )
            ->setQtyentered(1)
            ->setQtyordered(1)
            ->setQtyreserved(1);
        
        $date = new \DateTime("now"); 
        $Orderline
            ->setCreated( $date )
            ->setCreatedby( $user->getId() )
            ->setUpdated( $date )
            ->setUpdatedby( $user->getId() )
            ->setIsactive('Y')
            ->setDateordered( $date )
            ->setCOrderlineUu( $RSequence->findNextUU() );

        $RTax = new CTaxRepository($this->manager);
        $tax = $RTax->findBy([
            'ad_client_id' => Constant::AD_Client_ID,
            'c_taxcategory_id' => $product->getCTaxcategoryId(),
            'sopotype' => ['S', 'B'],
            'isdefault' => 'Y',
            'isactive' => 'Y'
        ]);
        $Orderline->setCTaxId( $tax[0]->getId() );
        
        $em = $this->manager->getManager();
        $em->persist($Orderline);

        $ROrder = new COrderRepository($this->manager);
        $order = $ROrder->find( $order->getId() );
        $order->setTotallines( $order->getTotallines() + $Orderline->getLinenetamt() );
        $order->setGrandtotal( $order->getTotallines() );
        $em->persist($order);

        try {
            $em->flush();
            $em->clear();

            $html = 
            '<tr class="text-center orderline" id="'. base64_encode($Orderline->getId()) .'">
                <td class="text-center">'   . $Orderline->getLine() .'</td>
                <td class="text-left">'     . $Orderline->getMProduct()->getName() .'</td>
                <td class="text-center">'   . $Orderline->getMProduct()->getValue() .'</td>

                <td class="text-right prc" id="'. base64_encode($Orderline->getId() . "prc") .'">
                    <p onclick="App.Jaxon.Orderline.changePrc(\''. base64_encode($Orderline->getId()) .'\')">'. number_format($Orderline->getPriceactual(), 2) .'</p>
                </td>

                <td class="text-right qty" id="'. base64_encode($Orderline->getId() . "qty") .'">
                    <p onclick="App.Jaxon.Orderline.changeQty(\''. base64_encode($Orderline->getId()) .'\')">'. number_format($Orderline->getQtyordered(), 2) .'</p>
                </td>

                <td class="text-right amt" id="'. base64_encode($Orderline->getId() . "amt") .'">
                    '. number_format($Orderline->getLinenetamt(), 2) .'
                </td>

                <td class="btn-action">
                    <i onclick="App.Jaxon.Base.actionConfirm(\'Orderline\', \'delete\', \''. base64_encode($Orderline->getId()) .'\')" class="fas fa-trash text-danger"></i>
                </td>
            </tr>';
            $jxnr
                ->prepend('tablaProductos', 'innerHTML', $html)
                ->assign('totallines', 'value', number_format($order->getTotallines(), 2))
                ->assign('grandtotal', 'value', number_format($order->getGrandtotal(), 2))
                ->script('$("#productModal").modal("hide")');
        } catch (Exception $e) {
            $jxnr->script(self::displayError("Oops!", "Ha fallado al crear la Linea", $e) );
        }

        return  $jxnr;
    }

    /**
     * Borra una linea de una orden temporal
     * #TODO: Crear metodo para reordenar lineas
     * 
     * @param string $C_Orderline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function delete(
        String $C_Orderline_ID
    )
    {
        $C_Orderline_ID = base64_decode($C_Orderline_ID);
        $ROrderline = new COrderlineRepository($this->manager);
        $orderline  = $ROrderline->find( $C_Orderline_ID );
        $oAmt = $orderline->getLinenetamt();
        $orderline->setIsactive('N');

        $em = $this->manager->getManager();
        $em->persist($orderline);

        $ROrder = new COrderRepository($this->manager);
        $order = $ROrder->find( $orderline->getCOrderId() );
        $order->setTotallines( $order->getTotallines() - $oAmt ); // Restar monto viejo de la linea
        $order->setGrandtotal( $order->getTotallines() );

        $em->persist($order);

        $jxnr = new Response;
        try {
            $em->flush();
            $em->clear();

            $jxnr
                ->remove(base64_encode($C_Orderline_ID))
                ->assign('totallines', 'value', number_format($order->getTotallines(), 2))
                ->assign('taxamt', 'value', number_format($order->getGrandtotal() - $order->getTotallines(), 2))
                ->assign('grandtotal', 'value', number_format($order->getGrandtotal(), 2));
        } catch (Exception $e) {
            $jxnr->script(self::displayError("Oops!", "Ha fallado al borrar la Linea", $e) );
        }

        return $jxnr;
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
                    <td>'.  number_format($RProduct->findStorage($pp->getMProductId(), $warehouse->getId()), 2) .'</td>
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
     * #TODO: Verificar las cantidades disponibles
     * 
     * @param string $C_Orderline_ID Identificador de la linea
     * @param string $qty Cantidad
     * 
     * @return object Jaxon\Response\Response
     */
    public function setQty(
        String $C_Orderline_ID,
        String $qty
    )
    {
        $C_Orderline_ID = base64_decode($C_Orderline_ID);
        $ROrderline = new COrderlineRepository($this->manager);
        $orderline  = $ROrderline->find( $C_Orderline_ID );
        $orderline->setQtyordered( $qty );
        $orderline->setQtyentered( $qty );
        $orderline->setQtyreserved( $qty );

        $oAmt = $orderline->getLinenetamt();
        $orderline->setLinenetamt( $orderline->getPriceactual() * $qty );
        
        $em = $this->manager->getManager();
        $em->persist($orderline);

        $ROrder = new COrderRepository($this->manager);
        $order = $ROrder->find( $orderline->getCOrderId() );
        $amt = $order->getTotallines() - $oAmt; // Restar monto viejo de la linea
        $order->setTotallines( $amt + $orderline->getLinenetamt() );
        $order->setGrandtotal( $order->getTotallines() );
        
        $em->persist($order);
        
        $jxnr = new Response;
        try {
            $em->flush();
            $em->clear();

            $jxnr
                ->assign(base64_encode($C_Orderline_ID . 'qty'), 'innerHTML', '<p onclick="App.Jaxon.Orderline.changeQty(\''. base64_encode($orderline->getId()) .'\')">'. number_format($qty, 2) .'</p>')
                ->assign(base64_encode($C_Orderline_ID . 'amt'), 'innerHTML', number_format($orderline->getLinenetamt(), 2))
                ->assign('totallines', 'value', number_format($order->getTotallines(), 2))
                ->assign('taxamt', 'value', number_format($order->getGrandtotal() - $order->getTotallines(), 2))
                ->assign('grandtotal', 'value', number_format($order->getGrandtotal(), 2));
        } catch (Exception $e) {
            $jxnr->script(self::displayError("Oops!", "Ha fallado el guardado automatico de la Linea", $e) );
        }

        return $jxnr;
    }

    /**
     * Cambiar precio de un producto en una linea 
     * #TODO: Verificar las cantidades disponibles
     * 
     * @param string $C_Orderline_ID Identificador de la linea
     * @param string $prc Precio
     * 
     * @return object Jaxon\Response\Response
     */
    public function setPrc(
        String $C_Orderline_ID,
        String $prc
    )
    {
        $C_Orderline_ID = base64_decode($C_Orderline_ID);
        $ROrderline = new COrderlineRepository($this->manager);
        $orderline  = $ROrderline->find( $C_Orderline_ID );
        $orderline->setPriceactual($prc);
        $orderline->setPriceentered($prc);
        $orderline->setPricelist($prc);

        $oAmt = $orderline->getLinenetamt();
        $orderline->setLinenetamt( $orderline->getPriceactual() * $orderline->getQtyentered() );
        
        $em = $this->manager->getManager();
        $em->persist($orderline);

        $ROrder = new COrderRepository($this->manager);
        $order = $ROrder->find( $orderline->getCOrderId() );
        $amt = $order->getTotallines() - $oAmt; // Restar monto viejo de la linea
        $order->setTotallines( $amt + $orderline->getLinenetamt() );
        $order->setGrandtotal( $order->getTotallines() );
        
        $em->persist($order);
        
        $jxnr = new Response;
        try {
            $em->flush();
            $em->clear();

            $jxnr
                ->assign(base64_encode($C_Orderline_ID . 'prc'), 'innerHTML', '<p onclick="App.Jaxon.Orderline.changePrc(\''. base64_encode($orderline->getId()) .'\')">'. number_format($prc, 2) .'</p>')
                ->assign(base64_encode($C_Orderline_ID . 'amt'), 'innerHTML', number_format($orderline->getLinenetamt(), 2))
                ->assign('totallines', 'value', number_format($order->getTotallines(), 2))
                ->assign('taxamt', 'value', number_format($order->getGrandtotal() - $order->getTotallines(), 2))
                ->assign('grandtotal', 'value', number_format($order->getGrandtotal(), 2));
        } catch (Exception $e) {
            $jxnr->script(self::displayError("Oops!", "Ha fallado el guardado automatico de la Linea", $e) );
        }

        return $jxnr;
    }
}

?>