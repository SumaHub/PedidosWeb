<?php

namespace App\Controller;

use App\Constant;
use App\Entity\Main\MProductdownload;
use App\Repository\Main\AdSequenceRepository;
use App\Repository\Main\MProductdownloadRepository;
use App\Repository\Main\MProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductdownloadController extends BaseController
{
    public $code = false;

    public $link;

    public $name;

    public $publicDir;

    public $targetDir;

    public function __construct()
    {
        parent::__construct();

        $this->publicDir = 'assets/img/product/';
        $this->targetDir = dirname(__FILE__) . '/../../public/' . $this->publicDir;
    }

    /**
     * Ruta para agregar imagen al producto
     * @Route("/producto/imagen/add/{codigo}", name="create_image")
     * 
     * @param string $codigo Codigo del producto
     * @param ManagerRegistry $manager Doctrine Manager
     *
     * @return \Symfony\Component\HttpFoundation\Response Vista
     */
    public function create(String $codigo, ManagerRegistry $manager): Response
    {
        if ( !$this->VerifySession() ) 
            return $this->logout();
        
        $user = $this->session->get('user', null);

        $this->upload($_FILES, $codigo);

        if( $this->code ) {
            $RProduct = new MProductRepository($manager);
            $product = $RProduct->findOneBy(['value' => $codigo]);
            $date = new \DateTime("now");

            $RSequence = new AdSequenceRepository($manager);
            $RProductdownload = new MProductdownloadRepository($manager); 
            $Productdownload = new MProductdownload();
            $Productdownload
                ->setMProductdownloadId( $RSequence->findNextSequence($RProductdownload->sequence) )
                ->setAdClientId( Constant::AD_Client_ID )
                ->setAdOrgId( 0 )
                ->setIsactive( 'Y' )
                ->setCreated( $date )
                ->setCreatedby( $user->getId() )
                ->setUpdated( $date )
                ->setUpdatedby( $user->getId() )
                ->setMProduct( $product )
                ->setMProductId( $product->getId() )
                ->setName( $this->name )
                ->setDownloadurl( 'http://pedido.sumagroups.com/' . $this->publicDir . $codigo . '/' . $this->name)
                ->setMProductdownloadUu( $RSequence->findNextUU() );

            $manager = $manager->getManagerForClass(MProductdownload::class);
            $manager->persist($Productdownload);
            $manager->flush();
        }

        $response = new Response(
            "{id: '". $Productdownload->getId() ."'}",
            $Productdownload->getId() > 0 ? Response::HTTP_ACCEPTED : Response::HTTP_NOT_ACCEPTABLE,
            ['Content-Type', 'application/json']
        );

        return $response;
    }

    public function create_dir($folder)
    {
        if (!is_dir($this->targetDir . $folder)) {
            mkdir($this->targetDir . $folder);
        }

        return $folder;
    }

    /**
     * Ruta para eliminar imagen del producto
     * @Route("/producto/imagen/delete/{productdownload_id}", name="delete_image")
     * 
     * @param int $id Identificador del registro
     * @param ManagerRegistry $manager Doctrine Manager
     */
    public function delete(Int $id, ManagerRegistry $manager) 
    { 
        if ( !$this->VerifySession() ) 
            return $this->logout();
        
        $user = $this->session->get('user', null);

        $RProductdownload = new MProductdownloadRepository($manager);
        $Productdownload = $RProductdownload->find( $id );
        $Productdownload->setIsactive( 'N' );

        $manager = $manager->getManagerForClass(MProductdownload::class);
        $manager->persist($Productdownload);
        $manager->flush();

        $response = new Response(
            "{id: '". $Productdownload->getId() ."'}",
            $Productdownload->getId() > 0 ? Response::HTTP_ACCEPTED : Response::HTTP_NOT_ACCEPTABLE,
            ['Content-Type', 'application/json']
        );

        return true; 
    }

    public function upload(Array $file, String $folder)
    {
        if(!empty($file)){
            $tempFile   = $file['file']['tmp_name'];
            $this->name = explode('.', $file['file']['name']);    
            $this->name = $folder . date('YmdHis') . rand(1, intval(date('Hisu'))) . '.' . $this->name[1];        
            $newFile    = $this->targetDir . $this->create_dir($folder) . '/' . $this->name;
            $this->code = move_uploaded_file($tempFile, $newFile);
        }
    }
}

?>