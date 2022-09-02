<?php

namespace App\Jaxon;

use App\Constant;
use App\Entity\Main\MRequisitionline;
use App\Entity\Main\SmPrecioEstimado;
use App\Repository\Main\AdSequenceRepository;
use App\Repository\Main\MProductdownloadRepository;
use App\Repository\Main\MProductpriceRepository;
use App\Repository\Main\MProductRepository;
use App\Repository\Main\MRequisitionlineRepository;
use App\Repository\Main\MRequisitionRepository;
use App\Repository\Main\SmPrecioEstimadolineRepository;
use Exception;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Session\Session;

#TODO: Calcular cantidades disponibles de un producto (Cantidad Reservada + Requisiciones en Progreso)
class Requisitionline extends Base
{
    /**
     * Habilitar campo para cambiar cantidades
     * de una linea en la plantilla
     * 
     * @param string $M_Requisitionline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function changeQty(
        String $M_Requisitionline_ID
    )
    {
        $M_Requisitionline_ID = base64_decode($M_Requisitionline_ID);
        $RRequisitionline = new MRequisitionlineRepository($this->manager);
        $requisitionline  = $RRequisitionline->find( $M_Requisitionline_ID );
        $qty = $requisitionline->getQty();

        $jxnr = new Response;
        $input= 
            '<input 
                type="number"
                step="0.01"
                value="'. number_format($qty, 2) .'"
                onblur="App.Jaxon.Requisitionline.setQty(\''. base64_encode($M_Requisitionline_ID) .'\', this.value)"
            >';
        return $jxnr->assign(base64_encode($M_Requisitionline_ID . 'qty'), 'innerHTML', $input);
    }

    /**
     * Habilitar campo para cambiar precios
     * de una linea en la plantilla
     * 
     * @param string $M_Requisitionline_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function changePrc(
        String $M_Requisitionline_ID
    )
    {
        $M_Requisitionline_ID = base64_decode($M_Requisitionline_ID);
        $RRequisitionline = new MRequisitionlineRepository($this->manager);
        $requisitionline  = $RRequisitionline->find( $M_Requisitionline_ID );
        $prc = $requisitionline->getPriceactual();

        $jxnr = new Response;
        $input= 
            '<input 
                type="number"
                step="0.01"
                value="'. number_format($prc, 2) .'"
                onblur="App.Jaxon.Requisitionline.setPrc(\''. base64_encode($M_Requisitionline_ID) .'\', this.value)"
            >';
        return $jxnr->assign(base64_encode($M_Requisitionline_ID . 'prc'), 'innerHTML', $input);
    }

    /**
     * Crear linea en la plantilla
     * #TODO: Cambiar monto de la plantilla 
     * 
     * @param int $m_productprice_id Identificacdor del precio del producto
     * @param array $formData Datos de la plantilla
     * 
     * @return string/array Mensaje de error / Datos de la linea
     */
    public function create(
        Int $m_productprice_id,
        Array $formData
    ): Response
    {
        $jxnr   = new Response;

        $user = $this->session->get('user', null);
        $requisition = $this->session->get('requisition', null);
        $warehouse = $this->session->get('warehouse', null);
        
        if( is_null($requisition) ){
            $JRequisition = new Requisition($this->manager);
            $requisition = $JRequisition->create($formData);

            $buttons = 
                '<div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <button id="cancel" class="mx-1 btn btn-lg btn-danger"><i class="fas fa-cog pr-2"></i>Borrar</button>
                                    <button id="process" class="mx-1 btn btn-lg btn-success"><i class="fas fa-cog pr-2"></i>Preparar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
                
            $jxnr
                ->assign('document', 'value', $requisition->getDocumentno())
                ->assign('docstatus', 'value', 'Borrador')
                ->append('contenedor', 'innerHTML', $buttons)
                ->setEvent('cancel', 'onclick', 'anular("'. base64_encode($requisition->getId()) .'")')
                ->setEvent('process', 'onclick', 'aprobar("'. base64_encode($requisition->getId()) .'")');
        }
        
        $RProductprice = new MProductpriceRepository($this->manager);
        $productprice = $RProductprice->find($m_productprice_id);

        $RProduct = new MProductRepository($this->manager);
        $product = $RProduct->find($productprice->getMProductId());
        
        // Verifica si el producto tiene stock
        if( $RProduct->findStorage($product->getId(), $warehouse->getId()) <= 0 ) 
            return $jxnr->script( self::displayError("Producto sin Stock", "Este producto no cuenta con cantidades disponibles.") );

        $RRequisitionline = new MRequisitionlineRepository($this->manager);
        // Verifica si el producto ya esta registrado en la orden
        if( count( $RRequisitionline->findBy(['m_requisition_id' => $requisition->getId(), 'm_product_id' => $product->getId(), 'isactive' => 'Y']) ) > 0 )
            return $jxnr->script( self::displayError("Producto Repetido", "Este producto se encuentra registrado, modifique la cantidad de ser necesario.") );

        $requisitionlines = $RRequisitionline->findBy(['m_requisition_id' => $requisition->getId()]);
        
        $RSequence = new AdSequenceRepository($this->manager);
        $Requisitionline = new MRequisitionline();
        $Requisitionline
            ->setAdClientId( Constant::AD_Client_ID )
            ->setAdOrgId( $requisition->getAdOrgId() )
            ->setCBpartnerId( $requisition->getCBpartner()->getId() )
            ->setMRequisitionId( $requisition->getId() )
            ->setMRequisitionlineId( $RSequence->findNextSequence( $RRequisitionline->sequence ) )
            ->setCUomId( $product->getCUomId() )
            ->setLine( (count($requisitionlines) + 1) * 10 )
            ->setMProduct( $product )
            ->setPriceactual( 0 )
            ->setPricelist( 0 )
            ->setPriceoverride( 0 )
            ->setPriceoverride2( 0 )
            ->setLinenetamt( 0 )
            ->setTotalpriceprom( 0 )
            ->setSumpriceprom( 0 )
            ->setSumpricebreak( 0 )
            ->setQty(1)
            ->setQtyplan( 1 )
            ->setQtyplan2( 1 );
        //
        $date = new \DateTime("now"); 
        $Requisitionline
            ->setCreated( $date )
            ->setCreatedby( $user->getId() )
            ->setUpdated( $date )
            ->setUpdatedby( $user->getId() )
            ->setIsactive('Y')
            ->setMRequisitionlineUu( $RSequence->findNextUU() );

        // Calculate Fields
        $precio = 0;
        $pricelist = $requisition->getMPricelist();
        $RPrecioEstimadoline = new SmPrecioEstimadolineRepository($this->manager);
        $pEstimacionline = $RPrecioEstimadoline->findLastEstimacionByPricelist($pricelist->getId(), $product->getId(), SmPrecioEstimado::$DOCSTATUS_PROCESSED);
        if ( !is_null($pEstimacionline) ) {
            $pProcessed = $RPrecioEstimadoline->findLastEstimacion($product->getId(), SmPrecioEstimado::$DOCSTATUS_PROCESSED);
            $Requisitionline
                ->setLinenetamt( $pProcessed->getPricestd() )
                ->setPriceactual( $pProcessed->getPricestd() )
                ->setPricelist( $pProcessed->getPricestd() )
                ->setPriceoverride( $pProcessed->getPricestd() )
                ->setPriceoverride2( $pProcessed->getPricestd() )
                ->setSumpriceprom( $pProcessed->getPricestd() )
                ->setTotalpriceprom( $pProcessed->getPricestd() );
            //
            $precio = $pEstimacionline->getPricestd();
            if ($pEstimacionline->getPricestd() == 0) {
                $pPromo = $RPrecioEstimadoline->findLastEstimacion($product->getId(), SmPrecioEstimado::$DOCSTATUS_FORPROMOTION, "Y");
                $precio = $pPromo->getPricestd();
            }
            //
            $Requisitionline
                ->setDesiredprice( $precio )
                ->setTotalpricebreak( $precio )
                ->setSumpricebreak( $precio );

            // Informacion de Estimacion - Promocion
            $pEstimacionlinePromotion = $RPrecioEstimadoline->findLastEstimacion($product->getId(), SmPrecioEstimado::$DOCSTATUS_FORPROMOTION, "Y");
            if ( !is_null($pEstimacionlinePromotion) ) {
                $pEstimacionPromotion = $pEstimacionlinePromotion->getSmPrecioEstimado();
                $info = 
                    "Monto Flete: " . $pEstimacionPromotion->getFletexcontenedor() . 
                    ", Monto Flete x Pieza: " . $pEstimacionlinePromotion->getFletexpieza() .
                    ", Monto Nacionalizacion: " . $pEstimacionPromotion->getNacionalizacionxcontenedor() .
                    ", Monto Nacionalizacion x Pieza: " . $pEstimacionlinePromotion->getImpuestoxpieza() . 
                    ", Utilidad Deseada: " . $pEstimacionPromotion->getUtilidaddeseada();
                $Requisitionline->setInformation( $info );
            }

            // Informacion de Estimacion
            $pEstimacion = $pEstimacionline->getSmPrecioEstimado();
            $info2 = 
                "Monto Flete: " . $pEstimacion->getFletexcontenedor() .
                ", Monto Flete x Pieza: " . $pEstimacionline->getFletexpieza() .
                ", Monto Nacionalizacion: " . $pEstimacion->getNacionalizacionxcontenedor() .
                ", Monto Nacionalizacion x Pieza: " . $pEstimacionline->getImpuestoxpieza() . 
                ", Utilidad Deseada: " . $pEstimacion->getUtilidaddeseada();
            $Requisitionline->setInformation2( $info2 );
        } 
        //
        $em = $this->manager->getManager();
        $em->persist($Requisitionline);
        //
        $RRequisition = new MRequisitionRepository($this->manager);
        $requisition = $RRequisition->find( $requisition->getId() );
        $requisition->setTotallines( $requisition->getTotallines() + $Requisitionline->getLinenetamt() );
        $em->persist($requisition);
        //
        try {
            $em->flush();
            $em->clear();

            $image = $Requisitionline->getMProduct()->getMProductdownload()->first();
            $url = $image ? $image->getDownloadurl() : '/assets/img/default-150x150.png';

            $html = 
            '<tr class="text-center line" id="'. base64_encode($Requisitionline->getId()) .'">
                <td class="text-center">'   . $Requisitionline->getLine() .'</td>
                
                <td class="text-center">
                    <a href="'. $url .'" data-toggle="lightbox" data-title="'. $Requisitionline->getMProduct()->getValue() .'">
                        <img src="'. $url .'" alt="'. $Requisitionline->getMProduct()->getValue() .'" width="50">
                    </a>
                </td>
                
                <td class="text-left">'. $Requisitionline->getMProduct()->getName() .'</td>
                <td class="text-center">'. $Requisitionline->getMProduct()->getValue() .'</td>

                <td class="text-right prc" id="'. base64_encode($Requisitionline->getId() . "prc") .'">
                    <p onclick="App.Jaxon.Requisitionline.changePrc(\''. base64_encode($Requisitionline->getId()) .'\')">'. number_format($Requisitionline->getPriceactual(), 2) .'</p>
                </td>

                <td class="text-right qty" id="'. base64_encode($Requisitionline->getId() . "qty") .'">
                    <p onclick="App.Jaxon.Requisitionline.changeQty(\''. base64_encode($Requisitionline->getId()) .'\')">'. number_format($Requisitionline->getQty(), 2) .'</p>
                </td>

                <td class="text-right amt" id="'. base64_encode($Requisitionline->getId() . "amt") .'">
                    '. number_format($Requisitionline->getLinenetamt(), 2) .'
                </td>

                <td class="btn-action">
                    <i onclick="App.Jaxon.Base.actionConfirm(\'Requisitionline\', \'delete\', \''. base64_encode($Requisitionline->getId()) .'\')" class="fas fa-trash text-danger"></i>
                </td>
            </tr>';
            $jxnr
                ->prepend('tablaProductos', 'innerHTML', $html)
                ->assign('document', 'value', $requisition->getDocumentno())
                ->assign('totallines', 'value', number_format($requisition->getTotallines(), 2))
                ->script('$("#productModal").modal("hide")');
        } catch (Exception $e) {
            $jxnr->script(self::displayError("Oops!", "Ha fallado al crear la Linea", $e) );
        }

        return  $jxnr;
    }

    /**
     * Borra una linea de una plantilla
     * #TODO: Crear metodo para reordenar lineas
     * 
     * @param string $M_Requisition_ID Identificador de la linea
     * 
     * @return object Jaxon\Response\Response
     */
    public function delete(
        String $M_Requisition_ID
    )
    {
        $M_Requisition_ID = base64_decode($M_Requisition_ID);
        $RRequisitionline = new MRequisitionlineRepository($this->manager);
        $requisitionline  = $RRequisitionline->find( $M_Requisition_ID );
        $oAmt = $requisitionline->getLinenetamt();
        $requisitionline->setIsactive('N');

        $em = $this->manager->getManager();
        $em->persist($requisitionline);

        $RRequisition = new MRequisitionRepository($this->manager);
        $requisition = $RRequisition->find( $requisitionline->getMRequisitionId() );
        $requisition->setTotallines( $requisition->getTotallines() - $oAmt ); // Restar monto viejo de la linea

        $em->persist($requisition);

        $jxnr = new Response;
        try {
            $em->flush();
            $em->clear();

            $jxnr
                ->remove(base64_encode($M_Requisition_ID))
                ->assign('totallines', 'value', number_format($requisition->getTotallines(), 2));
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
        $RProductdownload = new MProductdownloadRepository($this->manager);

        // La organizacion debe ser seleccionada para listar los productos
        if(!isset($organization) || $formData['organization'] == 0)
            return $jxnr->script('Swal.fire("Oops!", "Primero debe seleccionar una organizaci&oacute;n!", "warning")');

        // La lista de precio debe ser seleccionada para mostrar los precios de los articulos
        if(!isset($formData['pricelist']) || $formData['pricelist'] == 0)
            return $jxnr->script('Swal.fire("Oops!", "No has seleccionado una lista de precio!", "warning")');

        /** Datos */
        $pps = $RProductprice->findProductPrices($formData['organization'], $formData['pricelist'], $value); 

        $html= '<table id="productTable" class="table"><thead><tr><th>Marca</th><th>Nombre</th><th>C&oacute;digo</th><th>Imagen</th><th>Cantidad</th><th>Precio</th></tr></thead><tbody>';
        if( count($pps) > 0) {
            foreach ($pps as $pp) {
                $storage = $RProduct->findStorage($pp->getMProductId(), $warehouse->getId());
                if ($storage < 1)
                    continue; 

                $img = $RProductdownload->findOneBy(['m_product_id' => $pp->getMProductId(), 'isactive' => "Y"]);
                $url = is_null($img) ? '/assets/img/default-150x150.png' : $img->getDownloadurl() ;
                $html .= 
                '<tr style="font-size: 0.9rem;">
                    <td>'.$pp->getMProduct()->getSmMarca()->getName().'</td>
                    <td>
                        <a href="#"  onclick="App.Jaxon.Requisitionline.create(\''. $pp->getId() .'\', jaxon.getFormValues(\'requisition\'))">
                            '.$pp->getMProduct()->getName().'
                        </a>
                    </td>
                    <td>'.$pp->getMProduct()->getValue().'</td>
                    <td>
                        <a href="'.$url.'" data-toggle="lightbox" data-title="'.$pp->getMProduct()->getName().'">
                            <img src="'.$url.'" alt="'.$pp->getMProduct()->getName().'" width="50">
                        </a>
                    </td>
                    <td>'.  number_format($storage, 2) .'</td>
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
     * @param string $M_Requisition_ID Identificador de la linea
     * @param string $qty Cantidad
     * 
     * @return object Jaxon\Response\Response
     */
    public function setQty(
        String $M_Requisition_ID,
        String $qty
    )
    {
        $M_Requisition_ID = base64_decode($M_Requisition_ID);
        $RRequisitionline = new MRequisitionlineRepository($this->manager);
        $requisitionline  = $RRequisitionline->find( $M_Requisition_ID );
        //        
        $RRequisition = new MRequisitionRepository($this->manager);
        $requisition = $RRequisition->find( $requisitionline->getMRequisitionId() );
        //
        $oAmt = $requisitionline->getLinenetamt(); // Monto viejo
        $oQty = $requisitionline->getQty(); // Cantidad viejo
        //
        if ( $requisition->getDocstatus() != "IP" )
            $requisitionline->setQtyplan2( $oQty );
        else 
            $requisitionline->setQtyplan( $oQty );
        //
        $requisitionline
            ->setQty( $qty )
            ->setLinenetamt( $requisitionline->getPriceactual() * $qty )
            ->setTotalpriceprom( $requisitionline->getLinenetamt() );
        //
        $em = $this->manager->getManager();
        $em->persist($requisitionline);
        //
        $amt = $requisition->getTotallines() - $oAmt; // Restar monto viejo de la linea
        $requisition->setTotallines( $amt + $requisitionline->getLinenetamt() );
        $em->persist($requisition);
        
        $jxnr = new Response;
        try {
            $em->flush();
            $em->clear();

            $jxnr
                ->assign(base64_encode($M_Requisition_ID . 'qty'), 'innerHTML', '<p onclick="App.Jaxon.Requisitionline.changeQty(\''. base64_encode($requisitionline->getId()) .'\')">'. number_format($qty, 2) .'</p>')
                ->assign(base64_encode($M_Requisition_ID . 'amt'), 'innerHTML', number_format($requisitionline->getLinenetamt(), 2))
                ->assign('totallines', 'value', number_format($requisition->getTotallines(), 2));
        } catch (Exception $e) {
            $jxnr->script(self::displayError("Oops!", "Ha fallado el guardado automatico de la Linea", $e) );
        }

        return $jxnr;
    }

    /**
     * Cambiar precio de un producto en una linea 
     * #TODO: Alterar los breakeven
     * 
     * @param string $M_Requisitionline_ID Identificador de la linea
     * @param string $prc Precio
     * 
     * @return object Jaxon\Response\Response
     */
    public function setPrc(
        String $M_Requisitionline_ID,
        String $prc
    )
    {
        $M_Requisitionline_ID = base64_decode($M_Requisitionline_ID);
        $RRequisitionline = new MRequisitionlineRepository($this->manager);
        $requisitionline  = $RRequisitionline->find( $M_Requisitionline_ID );
        //
        $RRequisition = new MRequisitionRepository($this->manager);
        $requisition = $RRequisition->find( $requisitionline->getMRequisitionId() );
        //
        $oAmt = $requisitionline->getLinenetamt(); // Monto viejo
        $oPrc = $requisitionline->getPriceactual(); // Precio viejo
        //
        if ( $requisition->getDocstatus() != "IP" )
            $requisitionline->setPriceoverride( $oPrc );
        else 
            $requisitionline->setPriceoverride2( $oPrc );
        //
        $requisitionline
            ->setPriceactual( $prc )
            ->setPricelist( $prc )
            ->setLinenetamt( $requisitionline->getPriceactual() * $requisitionline->getQty() )
            ->setTotalpriceprom( $requisitionline->getLinenetamt() );
        //
        $em = $this->manager->getManager();
        $em->persist($requisitionline);
        //
        $amt = $requisition->getTotallines() - $oAmt; // Restar monto viejo de la linea
        $requisition->setTotallines( $amt + $requisitionline->getLinenetamt() );
        $em->persist($requisition);
        
        $jxnr = new Response;
        try {
            $em->flush();
            $em->clear();

            $jxnr
                ->assign(base64_encode($M_Requisitionline_ID . 'prc'), 'innerHTML', '<p onclick="App.Jaxon.Requisitionline.changePrc(\''. base64_encode($requisitionline->getId()) .'\')">'. number_format($prc, 2) .'</p>')
                ->assign(base64_encode($M_Requisitionline_ID . 'amt'), 'innerHTML', number_format($requisitionline->getLinenetamt(), 2))
                ->assign('totallines', 'value', number_format($requisition->getTotallines(), 2));
        } catch (Exception $e) {
            $jxnr->script(self::displayError("Oops!", "Ha fallado el guardado automatico de la Linea", $e) );
        }

        return $jxnr;
    }
}

?>