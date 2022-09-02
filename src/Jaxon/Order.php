<?php

namespace App\Jaxon;

use App\Constant;
use App\Entity\AdUser;
use App\Entity\COrder;
use App\Model\Order as ModelOrder;
use App\Repository\AdSequenceRepository;
use App\Repository\AdUserRepository;
use App\Repository\CBpartnerLocationRepository;
use App\Repository\CBpartnerRepository;
use App\Repository\CCurrencyRepository;
use App\Repository\CDoctypeRepository;
use App\Repository\COrderRepository;
use App\Repository\CPaymenttermRepository;
use App\Repository\MPricelistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Exception;
use Jaxon\Response\Response;
use Symfony\Component\HttpFoundation\Request;

// TODO: Crear metodo para cambiar tercero de la orden "changeBpartner"
// TODO: Crear metodo para cambiar vendedor de la orden "changeSalesrep"

class Order extends Base
{

    /**
     * Crear orden con los datos del formulario
     * 
     * @param array $formData
     * 
     * @return \App\Entity\COrder Respuesta
     */
    public function create(
        Array $formData
    )
    {
        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);
        $warehouse = $this->session->get('warehouse', null);

        $RSequence = new AdSequenceRepository($this->manager);
        $ROrder = new COrderRepository($this->manager);
        $Order = new COrder();
        $Order->setCOrderId( $RSequence->findNextSequence($ROrder->sequence) );
        
        $date = new \DateTime("now"); 
        $Order
            ->setAdClientId( Constant::AD_Client_ID )
            ->setAdOrgId( $organization->getId() )
            ->setDescription( $formData['description'] )
            ->setIsactive('Y')
            ->setIssotrx('Y')
            ->setIsapproved('N')
            ->setCOrderUu( $RSequence->findNextUU() )
            ->setDateordered( $date )
            ->setDateacct( $date )
            ->setCreated( $date )
            ->setCreatedby( $user->getId() )
            ->setUpdated( $date )
            ->setUpdatedby( $user->getId() );

        $RUser = new AdUserRepository( $this->manager );
        $salesrep = $RUser->find( $formData['salesrep_id'] );
        $Order->setSalesrep( $salesrep );

        $RDoctype = new CDoctypeRepository($this->manager);
        $doctype = $RDoctype->find($formData['documenttype']);
        $sequence = $RSequence->find( $doctype->getDocnosequenceId() );
        $Order
            ->setCDoctype( $doctype )
            ->setCDoctypeId( $doctype->getId() )
            ->setDocumentno( $RSequence->findNextSequence($sequence, $Order) )
            ->setDocstatus('DR')
            ->setDocaction('PR')
            ->setProcessing('Y')
            ->setProcessed('N');

        $RBpartner = new CBpartnerRepository($this->manager);
        $partner = $RBpartner->find( !empty($formData['bpartner_id']) ? $formData['bpartner_id'] : Constant::C_BPartner_ID );
        $Order->setCBpartner( $partner );

        $RPartnerLocation = new CBpartnerLocationRepository($this->manager);
        $location = $RPartnerLocation->find( !empty($formData['bpartner_id']) ? $formData['address'] : Constant::C_BPartner_Location_ID ) ;
        $Order->setCBpartnerLocation( $location );
        
        $RPaymentterm = new CPaymenttermRepository($this->manager);
        $paymentterm = $RPaymentterm->find($formData['paymentterm']);
        $Order
            ->setCPaymentterm( $paymentterm )
            ->setPaymentrule('P');

        $RCurrency = new CCurrencyRepository($this->manager);
        $currency = $RCurrency->find($formData['currency']);
        $Order
            ->setCCurrency($currency)
            ->setInvoicerule('I')
            ->setDeliveryrule('A')
            ->setFreightcostrule('I')
            ->setDeliveryviarule('P')
            ->setPriorityrule('5');

        $RPricelist = new MPricelistRepository($this->manager);
        $pricelist = $RPricelist->find($formData['pricelist']);
        $Order
            ->setMPricelist($pricelist)
            ->setMWarehouseId( $formData['warehouse'] )
            ->setTotallines(0)
            ->setGrandtotal(0);

        $manager = $this->manager->getManager();
        $manager->persist($Order);

        try {
            $manager->flush();
            $manager->clear();
        } catch(Exception $e) {
            throw $e;
        }

        $this->session->set('order', $Order);

        return $Order;
    }

    /**
     * Elimina el pedido de las ordenes activas
     * 
     * @param int $Order_ID Identificador de la orden
     * @param bool $IsTemp Bandera de pedidos temporales
     * 
     * @return string/bool Mensaje de error / Estado de la consulta
     */
    public function delete(
        Int $Order_ID,
        Bool $IsTemp = true
    )
    {
        $jxnr   = new Response;
        $order  = new ModelOrder;

        if( $order->delete($Order_ID, $IsTemp) )
            $jxnr->redirect('/dashboard');
        else 
            $jxnr->script( self::displayError('Oops!', 'No fue posible eliminar el pedido.') );

        return $jxnr;
    }

    /**
     * Obtiene todos los terceros coincidentes
     * 
     * @param string $value Codigo del tercero
     * @param int $salesrep_id Identificador del vendedor
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function getBpartners(
        String $value = '', 
        Int $salesrep_id = 0
    ): Response
    {
        $jxnr = new Response;

        $user = $salesrep_id > 0 ? 
            $this->manager->getRepository(AdUser::class)->find($salesrep_id) : $this->session()->get('user', null);

        $RBpartner = new CBpartnerRepository($this->manager);
        $criteria_bp = [
            'iscustomer' => 'Y',
            'issummary' => 'N',
            'ismatriz' => 'N',
            'isactive' => 'Y'
        ];

        // Buscar terceros asignados
        if ( $user->getCBpartnerId() && $RBpartner->find($user->getCBpartnerId())->getIssalesrep() )
            $criteria_bp['c_bpartner_id'] = $RBpartner->findBySalesRep($user->getId());

        $bpartners = new ArrayCollection( $RBpartner->findBy($criteria_bp) );

        // Seleccionar terceros coincidentes
        if ( !empty($value) ) {
            $searchN = new Comparison('name', 'CONTAINS', $value); 
            $searchV = new Comparison('value', 'CONTAINS', $value); 
            $criteria = new Criteria();

            $criteria->where($searchN)->orWhere($searchV);
            $bpartners = $bpartners->matching($criteria);
        }

        $html = '<table id="bpartnerTable" class="table"><thead><tr><th>Nombre</th><th>C&oacute;digo</th></tr></thead><tbody>';
        if( count($bpartners) > 0 ) {
            foreach ($bpartners as $bpartner) {
                $html .= 
                '<tr class="btn-close" style="cursor: pointer;" onclick="App.Jaxon.Order.setBpartner(\''. $bpartner->getValue() .'\', $(\'#salesrep_id\').val() )">
                    <td>'. $bpartner->getName() .'</td>
                    <td>'. $bpartner->getValue() .'</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">NO SE HA ENCONTRADO NINGUN CLIENTE</td></tr>';
        }
        $html .= '</tbody></table>';
        $jxnr
            ->assign('bpartners', 'innerHTML', $html)
            ->script('$("#bpartnerTable").dataTable({ columnDefs: [{ orderable: true, targets: 0 }], order: [[1, "asc" ]], responsive: true, lengthChange: false, autoWidth: false, dom: \'<"row"<"col-sm-12 col-md-12"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>\', buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"] })');
        
        return $jxnr;
    }

    /**
     * Obtiene todos los vendedores coincidentes
     * 
     * @param string $name Nombre del vendedor
     * @param int $ad_user_id Identificador del usuario
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function getSalesreps(
        String $name = '', 
        Int $ad_user_id = 0
    ): Response
    {
        $salesreps = [];
        $RUser = new AdUserRepository($this->manager);
        $user = $this->session->get('user', null);
        if ( $user->getCBpartnerId() > 0 )
        {
            $RBpartner = new CBpartnerRepository($this->manager);
            $partner = $RBpartner->find( $user->getCBpartnerId() );
            if ( $partner->getIssalesrep() )
            {
                $smSalesreps = $partner->getSmSalesReps();
                foreach ($smSalesreps as $ss) {
                    array_push($salesreps, $RUser->find($ss->getSalesrepId()));
                }
            }
        } else {
            $salesreps = $RUser->findSalesrep($name);
        }

        $html = '<table id="salesrepTable" class="table"><thead><tr><th>Nombre</th><th>Descripci&oacute;n</th><th>Correo</th></tr></thead><tbody>';
        if( count($salesreps) > 0 ) {
            foreach ($salesreps as $salesrep) {
                $html .= 
                '<tr class="btn-close" style="cursor: pointer;" onclick="App.Jaxon.Order.setSalesrep(\''. $salesrep->getName() .'\')">
                    <td>'. $salesrep->getName() .'</td>
                    <td>'. $salesrep->getDescription() .'</td>
                    <td>'. $salesrep->getEmail() .'</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">NO SE HA ENCONTRADO NINGUN VENDEDOR</td></tr>';
        }
        $html .= '</tbody></table>';

        $jxnr = new Response;
        $jxnr
            ->assign('salesreps', 'innerHTML', $html)
            ->script('$("#salesrepTable").dataTable({ columnDefs: [{ orderable: true, targets: 0 }], order: [[1, "asc" ]], responsive: true, lengthChange: false, autoWidth: false })');
        
        return $jxnr;
    }

    /**
     * Obtiene el numero de la localizacion
     * 
     * @param int $c_bpartner_location_id Identificador de la localizacion
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function getPhone(
        Int $c_bpartner_location_id
    ): Response
    {
        $partnerLocation = $this->manager->getRepository(CBpartnerLocation::class)->find($c_bpartner_location_id);

        $jxnr = new Response;
        if ($partnerLocation->getId() > 0) 
            $jxnr->assign('phone', 'value', $partnerLocation->getPhone());
        else
            $jxnr->alert('No olvides seleccionar una direcion!');

        return $jxnr;
    }

    public function init( String $c_order_id = null )
    {
        if ( isset($c_order_id) ) {
            $ROrder = new COrderRepository($this->manager);
            $order = $ROrder->find( base64_decode($c_order_id) );
            $this->session->set('order', $order);
        }

        $jxnr = new Response;
        if( is_null($c_order_id) || $order->getDocstatus() == 'DR' ) {
            $jxnr
                ->setEvent('organization', 'onchange', rq('App.Jaxon.Organization')->call('getPricelist', pm()->select('organization')))
                // Cliente
                ->setEvent('address', 'onchange', rq('App.Jaxon.Order')->call('getPhone', pm()->select('address')))
                ->setEvent('getBpartners', 'onclick', rq('App.Jaxon.Order')->call('getBpartners', pm()->input('bpartner_value'), pm()->input('salesrep_id')))
                ->setEvent('setBpartner', 'onclick', rq('App.Jaxon.Order')->call('setBpartner', pm()->input('bpartner'), pm()->input('salesrep_id')))
                ->setEvent('bpartner_id', 'onchange', rq('App.Jaxon.Order')->call('changeBpartner', pm()->input('bpartner_id')))
                // Vendedor
                //->setEvent('getSalesreps', 'onclick', rq('App.Jaxon.Order')->call('getSalesreps', pm()->input('salesrep_value')))
                //->setEvent('setSalesrep', 'onclick', rq('App.Jaxon.Order')->call('setSalesrep', pm()->input('salesrep')))
                //->setEvent('salesrep_id', 'onchange', rq('App.Jaxon.Order')->call('changeSalesrep', pm()->input('salesrep_id')))
                // Productos
                ->setEvent('getProducts', 'onclick', rq('App.Jaxon.Orderline')->call('getProducts', pm()->input('product_value'), pm()->form('order')));
        }

        if( !is_null($c_order_id) )
            $jxnr->setEvent('process', 'onclick', rq('App.Jaxon.Order')->call('process',  $c_order_id));

        return $jxnr;
    }

    public function process(
        String $C_Order_ID
    ): Response
    {
        $response = Request::create('/pedido/process/' . $C_Order_ID);
        $content = json_decode( $response->getContent() );

        $alert = '';
        if ( $content->status == 'success' ) {
            $alert = "Swal.fire({
                icon: 'success',
                title: 'Orden Procesada!',
                text: 'La orden fue procesada con exito.'
            });";
        } else {
            $alert = "Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'La orden no pudo ser procesada.'
            });";
        }

        $jxnr = new Response();
        return $jxnr->script($alert);
    }

    /**
     * Asigna el tercero a la orden
     * #TODO: Buscar coincidencias solo entre los clientes del vendedor
     * 
     * @param string $value Codigo del tercero
     * @param int $salesrep_id Identificador del vendedor
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function setBpartner(
        String $value = '', 
        Int $salesrep_id = 0
    ): Response
    {
        $jxnr = new Response;

        if(empty($value))
            return $jxnr->script('Swal.fire("Oops!", "El campo no puede estar vacio!", "warning")');
        
        $RBpartner = new CBpartnerRepository($this->manager);
        $partner = $RBpartner->findOneBy(['value' => $value]);
        $bpLocation = $partner->getCBpartnerLocation();
        $address = '';
        $i = 0;
        foreach ($bpLocation as $location) {
            $checked = '';
            if($i == 0) {
                $phone = $location->getPhone();
                $checked = 'checked="true"';
            }

            $address .= '<option value="'. $location->getId() .'" '. $checked .'>'. $location->getCLocation()->getAddress1() .'</option>';
            $i++;
        }
        
        $user = $salesrep_id > 0 ? 
            $this->manager->getRepository(AdUser::class)->find($salesrep_id) : $this->session()->get('user', null);
        $description = 'Pedido Web: ' . $user->getName() . ' - ' . $partner->getName();
        $jxnr
            ->assign('bpartner_id', 'value', $partner->getId())
            ->assign('bpartner', 'value', $partner->getValue())
            ->assign('description', 'value', $description)
            ->assign('address', 'innerHTML', $address)
            ->assign('phone', 'value', $phone)
            ->assign('address', 'disabled', false)
            ->script('$("#bpartnerModal").modal("hide")');

        return $jxnr;
    }

    /**
     * Asigna el vendedor a la orden
     * #TODO: Busca que el vendedor sea de los asignados o el mismo
     * 
     * @param string $name Nombre del vendedor
     * @param int $ad_user_id Identificador del usuario
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function setSalesrep(
        String $name = '', 
        Int $ad_user_id = 0
    ): Response
    {
        $jxnr = new Response;

        if(empty($name))
            return $jxnr->script('Swal.fire("Oops!", "El campo no puede estar vacio!", "warning")');
        
        $RUser = new AdUserRepository($this->manager);
        $user = $RUser->findOneBy(['name' => $name]);
        $jxnr
            ->assign('salesrep_id', 'value', $user->getId())
            ->assign('salesrep', 'value', $user->getName())
            ->clear('bpartner_value', 'value')
            ->clear('address', 'value')
            ->clear('phone', 'value')
            ->clear('bpartner', 'value')
            ->clear('bpartner_id', 'value')
            ->clear('bpartners')
            ->clear('description', 'value')
            ->script('$("#salesrepModal").modal("hide")');

        return $jxnr;
    }
}

?>