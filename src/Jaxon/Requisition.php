<?php

namespace App\Jaxon;

use App\Constant;
use App\Entity\Main\AdUser;
use App\Entity\Main\MRequisition;
use App\Repository\Main\AdOrginfoRepository;
use App\Repository\Main\AdSequenceRepository;
use App\Repository\Main\AdUserRepository;
use App\Repository\Main\CBpartnerLocationRepository;
use App\Repository\Main\CBpartnerRepository;
use App\Repository\Main\CDoctypeRepository;
use App\Repository\Main\CPaymenttermRepository;
use App\Repository\Main\MPricelistRepository;
use App\Repository\Main\MRequisitionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Exception;
use Jaxon\Response\Response;

// TODO: Crear metodo para cambiar tercero de la plantilla "changeBpartner"
// TODO: Crear metodo para cambiar vendedor de la plantilla "changeSalesrep"

class Requisition extends Base
{

    /**
     * Deshabilitar campos y botones del formulario
     * 
     * @return Response Respuesta
     */
    public function clear(): Response
    {
        $jxnr = new Response;
        $jxnr
            ->remove('cleanline')
            ->assign('docstatus', 'value', 'En Progreso')
            ->assign('organization', 'disabled', true)
            ->assign('bpartner', 'disabled', true)
            ->assign('documentint', 'disabled', true)
            ->assign('pricelist', 'disabled', true)
            ->assign('warehouse', 'disabled', true)
            ->remove('actions');
        return $jxnr;
    }

    /**
     * Crear plantilla con los datos del formulario
     * 
     * @param array $formData
     * 
     * @return \App\Entity\MRequisition Respuesta
     */
    public function create(
        Array $formData
    )
    {
        $user = $this->session->get('user', null);
        $organization = $this->session->get('organization', null);

        $ROrginfo = new AdOrginfoRepository($this->manager);
        $RSequence = new AdSequenceRepository($this->manager);
        $RRequisition = new MRequisitionRepository($this->manager);
        $Requisition = new MRequisition();
        $Requisition->setMRequisitionId( $RSequence->findNextSequence($RRequisition->sequence) );
        
        $orginfo = $ROrginfo->findOneBy(['ad_org_id' => $organization->getId()]);
        $date = new \DateTime("now"); 
        $Requisition
            ->setAdClientId( Constant::AD_Client_ID )
            ->setAdOrgId( $organization->getId() )
            ->setSmMarcaId( $orginfo->getSmMarcaId() )
            ->setDescription( $formData['description'] )
            ->setMRequisitionUu( $RSequence->findNextUU() )
            ->setDaterequired( $date )
            ->setDatedoc( $date )
            ->setCreated( $date )
            ->setCreatedby( $user->getId() )
            ->setUpdated( $date )
            ->setUpdatedby( $user->getId() );

        $documentint = isset($formData['documentint']) && $formData['documentint'] == "on" ? 'Y' : 'N' ;
        $Requisition
            ->setSmDocumentInt( $documentint )
            ->setIsactive('Y')
            ->setIsquatation('Y')
            ->setIsapproved('N')
            ->setIsapproved2('N')
            ->setIsapproved3('N')
            ->setPriorityrule('5');

        $RUser = new AdUserRepository( $this->manager );
        $salesrep = $RUser->find( $formData['salesrep_id'] );
        $Requisition
            ->setSalesrep( $salesrep )
            ->setAdUserId( $salesrep->getId() );

        $RDoctype = new CDoctypeRepository($this->manager);
        $doctype = $RDoctype->find($formData['documenttype']);
        $sequence = $RSequence->find( $doctype->getDocnosequenceId() );
        $Requisition
            ->setCDoctype( $doctype )
            ->setCDoctypeId( $doctype->getId() )
            ->setDocumentno( $RSequence->findNextSequence($sequence, $Requisition) )
            ->setDocstatus('DR')
            ->setDocaction('CO')
            ->setProcessed('N')
            ->setProcessing('N')
            ->setProcessedon(0)
            ->setPosted('N');

        $RBpartner = new CBpartnerRepository($this->manager);
        $partner = $RBpartner->find( !empty($formData['bpartner_id']) ? $formData['bpartner_id'] : Constant::C_BPartner_ID );
        $Requisition->setCBpartner( $partner );

        $RPartnerLocation = new CBpartnerLocationRepository($this->manager);
        $location = $RPartnerLocation->find( !empty($formData['bpartner_id']) ? $formData['address'] : Constant::C_BPartner_Location_ID ) ;
        $Requisition->setCBpartnerLocation( $location );
        
        $RPaymentterm = new CPaymenttermRepository($this->manager);
        $paymentterm = $RPaymentterm->find($formData['paymentterm']);
        $Requisition->setCPaymentterm( $paymentterm );

        $RPricelist = new MPricelistRepository($this->manager);
        $pricelist = $RPricelist->find($formData['pricelist']);
        $Requisition
            ->setMPricelist($pricelist)
            ->setMWarehouseId( $formData['warehouse'] )
            ->setTotallines(0);

        $manager = $this->manager->getManager();
        $manager->persist($Requisition);

        try {
            $manager->flush();
            $manager->clear();
        } catch(Exception $e) {
            throw $e;
        }

        $this->session->set('requisition', $Requisition);

        return $Requisition;
    }

    /**
     * Inactivamos la plantilla
     * 
     * @param string $m_requisition_id Identificador de la plantilla
     * 
     * @return string/bool Mensaje de error / Estado de la consulta
     */
    public function delete(
        String $m_requisition_id
    )
    {
        $jxnr   = new Response;
        $RRequisition = new MRequisitionRepository($this->manager);
        $requisition = $RRequisition->find( base64_decode($m_requisition_id) );
        $requisition->setIsactive('N');
        $manager = $this->manager->getManager();
        $manager->persist($requisition);

        try {
            $manager->flush();
            $manager->clear();

            $jxnr->redirect('/dashboard');
        } catch(Exception $e) {
            $jxnr->script( self::displayError('Oops!', 'No fue posible eliminar el pedido.', $e) );
        }           

        return $jxnr;
    }

    /**
     * Obtiene todos los terceros coincidentes
     * 
     * @param string $value Codigo del tercero
     * @param int $salesrep_id Identificador del vendedor
     * 
     * @return \Jaxon\Response\Response Respuesta
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
                '<tr class="btn-close" style="cursor: pointer;" onclick="App.Jaxon.Requisition.setBpartner(\''. $bpartner->getValue() .'\', $(\'#salesrep_id\').val() )">
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
     * @return \Jaxon\Response\Response Respuesta
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
                '<tr class="btn-close" style="cursor: pointer;" onclick="App.Jaxon.Requisition.setSalesrep(\''. $salesrep->getName() .'\')">
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
     * @return \Jaxon\Response\Response Respuesta
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

    /**
     * Inicializa las funciones del formulario
     * #TODO: Evaluar cantidades de lineas nuevamente
     * 
     * @param string $m_requisition_id Identificador de la plantilla
     * 
     * @return \Jaxon\Response\Response Respuesta
     */
    public function init( String $m_requisition_id = null )
    {
        if ( isset($m_requisition_id) ) {
            $RRequisition = new MRequisitionRepository($this->manager);
            $requisition = $RRequisition->find( base64_decode($m_requisition_id) );
            $this->session->set('requisition', $requisition);
        }

        $jxnr = new Response;
        if( is_null($m_requisition_id) || $requisition->getDocstatus() == 'DR' ) {
            $jxnr
                ->setEvent('organization', 'onchange', rq('App.Jaxon.Organization')->call('getPricelist', pm()->select('organization')))
                // Cliente
                ->setEvent('address', 'onchange', rq('App.Jaxon.Requisition')->call('getPhone', pm()->select('address')))
                ->setEvent('getBpartners', 'onclick', rq('App.Jaxon.Requisition')->call('getBpartners', pm()->input('bpartner_value'), pm()->input('salesrep_id')))
                ->setEvent('setBpartner', 'onclick', rq('App.Jaxon.Requisition')->call('setBpartner', pm()->input('bpartner'), pm()->input('salesrep_id')))
                ->setEvent('bpartner_id', 'onchange', rq('App.Jaxon.Requisition')->call('changeBpartner', pm()->input('bpartner_id')))
                // Vendedor
                //->setEvent('getSalesreps', 'onclick', rq('App.Jaxon.Requisition')->call('getSalesreps', pm()->input('salesrep_value')))
                //->setEvent('setSalesrep', 'onclick', rq('App.Jaxon.Requisition')->call('setSalesrep', pm()->input('salesrep')))
                //->setEvent('salesrep_id', 'onchange', rq('App.Jaxon.Requisition')->call('changeSalesrep', pm()->input('salesrep_id')))
                // Productos
                ->setEvent('getProducts', 'onclick', rq('App.Jaxon.Requisitionline')->call('getProducts', pm()->input('product_value'), pm()->form('requisition')));
        }

        if( !is_null($m_requisition_id) && $requisition->getDocstatus() == 'DR' ) {
            $jxnr
                ->setEvent('cancel', 'onclick', 'anular("'.$m_requisition_id.'")')
                ->setEvent('process', 'onclick', 'aprobar("'.$m_requisition_id.'")');
        }

        return $jxnr;
    }

    /**
     * Procesar plantilla
     * 
     * @param string $M_Requisition_ID Identificador de la plantilla
     * 
     * @return \Jaxon\Response\Response Respuesta
     */
    /* public function process(
        String $M_Requisition_ID
    ): Response
    {
        $response = Request::create('https://localhost:8000/plantilla/process/' . $M_Requisition_ID);
        $content = json_decode( $response->getContent() );

        $alert = '';
        if ( isset($content->status) && $content->status == 'success' ) {
            $alert = "Swal.fire({
                icon: 'success',
                title: 'Orden Procesada!',
                text: 'La orden fue procesada con exito.'
            });";
        } else {
            $alert = $this->displayError('Oops!', 'La orden no pudo ser procesada.', $content->message);
        }

        $jxnr = new Response();
        return $jxnr->script($alert);
    } */

    /**
     * Asigna el tercero a la orden
     * #TODO: Buscar coincidencias solo entre los clientes del vendedor
     * 
     * @param string $value Codigo del tercero
     * @param int $salesrep_id Identificador del vendedor
     * 
     * @return \Jaxon\Response\Response Respuesta
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
     * @return \Jaxon\Response\Response Respuesta
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