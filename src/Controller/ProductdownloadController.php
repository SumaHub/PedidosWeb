<?php

namespace App\Controller;

use App\Constant;
use App\Entity\MProduct;
use App\Entity\MProductdownload;
use App\Model\Product as ModelProduct;
use App\Repository\AdSequenceRepository;
use App\Repository\MProductdownloadRepository;
use App\Repository\MProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends BaseController
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
     * @Route("/producto/imagen/{codigo}", name="create")
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

        $product    = new ModelProduct();
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
                ->setMProductId( $product->getId() )
                ->setDownloadurl( 'http://pedido.sumagroups.com/' . $this->publicDir . $codigo . $this->name)
                ->setMProductdownloadUu( $RSequence->findNextUU() );

            $manager = $manager->getManagerForClass(MProduct::class);
            $manager->persist($Productdownload);
            $manager->flush();
        }

        if( $product->getId() > 0 ) 
            $this->delete($codigo . '/' . $this->name);

        $response = new Response(
            "{response: '". $product->getId() ."'}",
            $product->getId() > 0 ? Response::HTTP_ACCEPTED : Response::HTTP_NOT_ACCEPTABLE,
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

    public function delete(String $file) { return !is_file($this->targetDir . $file) ?: unlink($this->targetDir . $file) ; }

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