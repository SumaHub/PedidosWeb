<?php

namespace App\Command;

use App\Constant;
use App\Jaxon\Product;
use App\Jaxon\User;
use Knp\Snappy\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;

class ProductsWithoutPrice extends Command
{
    protected static $defaultName = 'app:products-without-price-report:send';

    protected $loader;

    protected $logger;

    protected $nodes = array(
        // 1er Nodo => [Junta Directiva]
        1 =>  [1000210]
    );

    protected $mailer;

    protected $pdf;

    protected $spreedsheet;

    protected $twig;

    protected $user;

    public function __construct(User $user, LoggerInterface $logger)
    {
        parent::__construct(null);
        
        $this->user     = $user;
        $this->loader   = new FilesystemLoader('templates');
        $this->logger   = $logger;

        // PDF options
        $this->pdf      = new Pdf('/usr/local/bin/wkhtmltopdf', [
            'page-width'    => '1240',  
            'page-height'   => '480',  
            'margin-bottom' => '20',
            'margin-left'   => '20',
            'margin-top'    => '20',
            'margin-right'  => '20',
            'orientation'   => 'Portrait',
            'enable-local-file-access' => true,
            'print-media-type' => true
        ]);
        $this->pdf->setTemporaryFolder(dirname(__DIR__) . '/../public/tmp/');

        // XLSX options
        $this->spreedsheet = new Spreadsheet();
        
        // TWIG options
        $this->twig     = new Environment($this->loader, [
            'cache'         => false,//'templates/cache',
            'autoescape'    => false,
            'debug'         => false
        ]);
        $this->twig->addExtension(new IntlExtension());
    }

    protected function configure() { $this->setDescription('Enviar reporte de productos nuevos.'); }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io     = new SymfonyStyle($input, $output);
        $productToUpdate = [];

        // Recorre los nodos
        $io->progressStart(count($this->nodes));
        foreach ($this->nodes as $node => $roles) {

            // Recorre los roles
            $io->progressAdvance();
            foreach ($roles as $rol) {

                // Busca los usuarios por rol
                $users = $this->user->getByRole($rol);
                
                // Verfica la cantidad de usuarios
                if($users->NumRows() > 0) {

                    // Recorre los usuarios
                    while($user = $users->fetchRow()){

                        // Verificar el correo
                        if ( isset($user['email']) && !empty(trim($user['email']))) {
                            
                            // 1) Obtener Productos
                            $products = Product::getWithoutPrice();

                            if ($products->numRows() > 0) {
                                // 2) Renderizar Vista
                                $html = $this->twig->render('modules/product/productsWithoutPrice.html', [
                                    'home_path'     => Constant::HOME_PATH,
                                    'title'         => 'Pedidos Web | Productos',
                                    'version'       => 'Versi&oacute;n 2.0.0',
                                    'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                                    'modulo'        => 'Productos',
                                    'node'          => $node,
                                    'user'          => $user,
                                    'products'      => $products
                                ]);

                                // 3) Obtener PDF
                                $pdf = $this->pdf->getOutputFromHtml($html);

                                // 4) Obtener XLSX
                                $xlsx = $this->export($products);

                                // 5) Preparar Correo
                                $email = (new Email())
                                    ->sender('no-reply@gplus.com.ve')
                                    ->addFrom('no-reply@gplus.com.ve')
                                    ->addReplyTo('no-reply@gplus.com.ve')
                                    ->to($user['email'])
                                    ->subject('Reporte de Productos sin Estimacion de Precio')
                                    ->attach($pdf, sprintf('ProductosSinEstimacionDePrecio%s.pdf', date('YmdHis')))
                                    ->attach(fopen($xlsx, 'r'), $xlsx)
                                    ->html('<h3>Reporte de Productos sin Estimacion de Precio</h3>');

                                $transport = new GmailSmtpTransport('notificacion@gplus.com.ve', 'W0Phqh7$');
                                
                                // 6) Enviar Correo
                                if ( $transport->send($email) ) {
                                    // 6.1) Borrar XLSX temporal
                                    unlink($xlsx);

                                    // 6.2) Listar Productos
                                    foreach ($products as $product) {
                                        $productToUpdate[$product['m_product_id']] = $product;
                                    }
                                } 
                            }
                        } else {
                            // 1) Consola
                            $io->warning("El usuario {$user['name']} no posee correo.");

                            // 2) Log
                            $this->logger->error("El usuario {$user['name']} no posee correo.");
                        }
                    }
                } else {
                    // 1) Consola
                    $io->warning("El rol {$rol} no posee usuarios activos.");

                    // 2) Log
                    $this->logger->error("El rol {$rol} no posee usuarios activos.");
                }
            }
        }

        // Actualiza los registro
        foreach ($productToUpdate as $id => $product) {
            // 1) Valores
            $message = 'Producto Nuevo: ' . $product['value'] . ' en Orden Nro ' . $product['order'];

            // 2) Consola
            $io->note($message);

            // 3) Log
            $this->logger->info($message);
        }
        
        $io->progressFinish();

        if (count($productToUpdate) > 0) {
            $io->info('Total: ('. count($productToUpdate) .') Facturas Vencidas');
            $io->success('Reporte de productos sin precio enviado!');
        } else {
            $io->success('No hay productos nuevos!');
        }

        return 1;
    }

    public function export($products)
    {
        // 1) Preparar XLSX
        $sheet = $this->spreedsheet->getActiveSheet();

        // 1.1) Columnas 
        $sheet->setTitle('Productos sin Estmacion de Precio');
        $sheet->getCell('A1')->setValue('Marca');
        $sheet->getCell('B1')->setValue('Organizacion');
        $sheet->getCell('C1')->setValue('Descrpcion');
        $sheet->getCell('D1')->setValue('Tercero');
        $sheet->getCell('E1')->setValue('Nro Documento');
        $sheet->getCell('F1')->setValue('Fecha de Orden');
        $sheet->getCell('G1')->setValue('Codigo');
        $sheet->getCell('H1')->setValue('Producto');
        $sheet->getCell('I1')->setValue('Estatus del Producto');
        $sheet->getCell('J1')->setValue('Cantidad Ordenada');
        $sheet->getCell('K1')->setValue('Cantidad Entregada');
        $sheet->getCell('L1')->setValue('Cantidad Reservada');
        $sheet->getCell('M1')->setValue('Fecha Prometida ETD');
        $sheet->getCell('N1')->setValue('Proforma');
        $sheet->getCell('O1')->setValue('Total Contenedores');
        $sheet->getCell('P1')->setValue('Pendiente por Embarcar');
        $sheet->getCell('Q1')->setValue('Gran Total');
        $sheet->getCell('R1')->setValue('Estado Documento');
        $sheet->getCell('S1')->setValue('Nro Documento');
        $sheet->getCell('T1')->setValue('Nro Factura Internacional');
        $sheet->getCell('U1')->setValue('Nro BL');
        $sheet->getCell('V1')->setValue('Total Contenedores');
        $sheet->getCell('W1')->setValue('Fecha ETA');
        $sheet->getCell('X1')->setValue('Fecha Contable');
        $sheet->getCell('Y1')->setValue('Estado Documento');
        $sheet->getCell('Z1')->setValue('Nro Documento');
        $sheet->getCell('AA1')->setValue('Estado Documento');
        $sheet->getCell('AB1')->setValue('Nro Documento');
        $sheet->getCell('AC1')->setValue('Fecha Contable');
        $sheet->getCell('AD1')->setValue('Estado Documento');

        // 1.2) Filas
        $sheet->fromArray($this->processData($products), null, 'A2', true);
        
        // 1.3) Documento
        $xlsx = new Xlsx($this->spreedsheet);
        $fileName = sprintf('ProductosSinPrecio%s.xlsx', date('YmdHis'));
        $xlsx->save($fileName);

        return $fileName;
    }

    private function processData($data): array
    {
        $products = [];
        foreach ($data as $product) {
            $products[] = [
                'Marca'                     => $product['brand'],
                'Organizacion'              => $product['organization'],
                'Descrpcion'                => $product['orderdescription'],
                'Tercero'                   => $product['bpartner'],
                'Nro Documento'             => $product['order'],
                'Fecha de Orden'            => $product['dateordered'],
                'Codigo'                    => $product['value'],
                'Producto'                  => $product['description'],
                'Estatus del Producto'      => $product['status'],
                'Cantidad Ordenada'         => $product['qtyordered'],
                'Cantidad Entregada'        => $product['qtyreceipt'],
                'Cantidad Reservada'        => $product['qtyreserved'],
                'Fecha Prometida ETD'       => $product['sm_fecha_etd'],
                'Proforma'                  => $product['sm_preform'],
                'Total Contenedores'        => $product['sm_containerqty'],
                'Pendiente por Embarcar'    => 0,
                'Gran Total'                => $product['grandtotal'],
                'Estado Documento'          => $product['orderstatus'],
                'Nro Documento'             => $product['receipt'],
                'Nro Factura Internacional' => $product['sm_invoicedocumentno'],
                'Nro BL'                    => $product['sm_invoicefiles'],
                'Total Contenedores'        => $product['sm_containerqty'],
                'Fecha ETA'                 => $product['sm_fecha_eta'],
                'Fecha Contable'            => $product['receiptdateacct'],
                'Estado Documento'          => $product['receiptstatus'],
                'Nro Documento'             => $product['confirm'],
                'Estado Documento'          => $product['confirmstatus'],
                'Nro Documento'             => $product['invoice'],
                'Fecha Contable'            => $product['invoicedateacct'],
                'Estado Documento'          => $product['invoicestatus']
            ];
        }

        return $products;
    }
}
