<?php

namespace App\Jaxon;

use App\Constant;
use App\Entity\COrder;
use App\Model\Order as ModelOrder;
use App\Repository\AdOrgRepository;
use App\Repository\AdSequenceRepository;
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
use Jaxon\Response\Response;

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
            ->setAdOrg( $organization->getId() )
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

        $RDoctype = new CDoctypeRepository($this->manager);
        $doctype = $RDoctype->find($formData['documenttype']);
        $sequence = $RSequence->find( $doctype->getDocnosequenceId() );
        $Order
            ->setCDoctype( $doctype )
            ->setCDoctypetargetId( $doctype->getId() )
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
            ->setMWarehouseId( $warehouse->getId() )
            ->setTotallines(0)
            ->setGrandtotal(0);

        $manager = $this->manager->getManagerForClass(COrder::class);
        $manager->persist($Order);
        $manager->flush();

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
     * @param int $ad_user_id Identificador del usuario
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function getBpartners(
        String $value = '', 
        Int $ad_user_id = 0
    ): Response
    {
        $jxnr = new Response;

        $user = $this->session->get('user', null);

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

        $html       = '<table id="bpartnerTable" class="table"><thead><tr><th>Nombre</th><th>C&oacute;digo</th></tr></thead><tbody>';
        if( count($bpartners) > 0 ) {
            foreach ($bpartners as $bpartner) {
                $html .= '<tr style="cursor: pointer;" onclick="App.Jaxon.Order.setBpartner(\''. $bpartner->getValue() .'\')"><td>'. $bpartner->getName() .'</td><td>'. $bpartner->getValue() .'</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">NO SE HA ENCONTRADO NINGUN CLIENTE</td></tr>';
        }
        $html       .= '</tbody></table>';
        $jxnr
            ->assign('bpartners', 'innerHTML', $html)
            ->script('$("#bpartnerTable").dataTable({ columnDefs: [{ orderable: true, targets: 0 }], order: [[1, "asc" ]], responsive: true, lengthChange: false, autoWidth: false, dom: \'<"row"<"col-sm-12 col-md-12"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>\', buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"] })');
        
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
        $jxnr = new Response;

        /** Repositorios */
        $partnerLocation = $this->manager->getRepository(CBpartnerLocation::class)->find($c_bpartner_location_id);

        if ($partnerLocation->getId() > 0) 
            $jxnr->assign('phone', 'value', $partnerLocation->getPhone());
        else
            $jxnr->alert('No olvides seleccionar una direcion!');

        return $jxnr;
    }

    public static function init()
    {
        $jxnr = new Response;
        $jxnr
            ->setEvent('organization', 'onchange', rq('App.Jaxon.Organization')->call('getPricelist', pm()->select('organization')))
            ->setEvent('address', 'onchange', rq('App.Jaxon.Order')->call('getPhone', pm()->select('address')))
            ->setEvent('getBpartners', 'onclick', rq('App.Jaxon.Order')->call('getBpartners', pm()->input('bpartner_value')))
            ->setEvent('setBpartner', 'onclick', rq('App.Jaxon.Order')->call('setBpartner', pm()->input('bpartner')))
            ->setEvent('tercero_id', 'onchange', rq('App.Jaxon.Order')->call('changeBpartner', pm()->input('bpartner_id')))
            ->setEvent('getProducts', 'onclick', rq('App.Jaxon.Orderline')->call('getProducts', pm()->input('product_value'), pm()->form('order')));
        return $jxnr;
    }

    /**
     * Obtiene todos los terceros coincidentes
     * 
     * @param string $value Codigo del tercero
     * @param int $c_location_id Identificador del usuario
     * 
     * @return Jaxon\Response\Response Respuesta
     */
    public function setBpartner(
        String $value = '', 
        Int $ad_user_id = 0
    ): Response
    {
        $jxnr = new Response;

        if(empty($value))
            return $jxnr->script('Swal.fire("Oops!", "El campo no puede estar vacio!", "warning")');
        
        /** Repositorios */
        $RBpartner = new CBpartnerRepository($this->manager);

        /** Datos */
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
        
        $user = $this->session->get('user');
        $description = 'Pedido Web: ' . $user->getName() . ' - ' . $partner->getName();
        $jxnr
            ->assign('bpartner_id', 'value', $partner->getId())
            ->assign('bpartner', 'value', $partner->getValue())
            ->assign('description', 'value', $description)
            ->assign('address', 'innerHTML', $address)
            ->assign('phone', 'value', $phone)
            ->assign('address', 'disabled', false);

        return $jxnr;
    }

    /**
     * Recalcular montos de la orden
     * 
     * @param int $SM_Order_ID Identificador de la orden
     * 
     * @return string/bool Mensaje de error / Estado del procedimiento
     */
    public static function syncAmt(
        Int $SM_Order_ID
    )
    {
        $model = new ModelOrder;
        return $model->sync_amt($SM_Order_ID);
    }

    /**
     * Reordenar lineas de una orden
     * 
     * @param int @SM_Order_ID Identificador de orden
     * 
     * @return string/array Mensaje de error / Estado del procedimiento
     */
    public static function syncLines(
        Int $SM_Order_ID
    )
    {
        $model = new ModelOrder;
        return $model->sync_lines($SM_Order_ID);
    }
}

?>