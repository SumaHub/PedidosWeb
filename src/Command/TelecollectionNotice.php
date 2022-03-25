<?php

namespace App\Command;

use App\Constant;
use App\Jaxon\Invoice;
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

class TelecollectionNotice extends Command
{
    protected static $defaultName = 'app:telecollection-notice-report:send';

    protected $loader;

    protected $logger;

    protected $nodes = array(
        // 1er Nodo => [Lider CxC, Gerente de CxC]
        1 =>  [1000066, 1000004]
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
            'page-width'    => '460',  
            'page-height'   => '780',  
            'margin-bottom' => '20',
            'margin-left'   => '20',
            'margin-top'    => '20',
            'margin-right'  => '20',
            'orientation'   => 'Landscape',
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

    protected function configure() { $this->setDescription('Enviar notificaciones de telecobranza.'); }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io     = new SymfonyStyle($input, $output);
        $noticesToUpdate = [];

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
                            
                            // 1) Obtener avisos
                            $notices = Invoice::getToCollect($user['id'], ($rol === 1000004));

                            if ($notices->numRows() > 0) {
                                // 2) Renderizar Vista
                                $html = $this->twig->render('modules/invoice/telecollectionNotice.html', [
                                    'home_path'     => Constant::HOME_PATH,
                                    'title'         => 'Pedidos Web | Telecobranza',
                                    'version'       => 'Versi&oacute;n 2.0.0',
                                    'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                                    'modulo'        => 'Telecobranza',
                                    'node'          => $node,
                                    'user'          => $user,
                                    'notices'       => $notices
                                ]);

                                // 3) Obtener PDF
                                $pdf = $this->pdf->getOutputFromHtml($html);

                                // 4) Obtener XLSX
                                $xlsx = $this->export($notices);

                                // 5) Preparar Correo
                                $email = (new Email())
                                    ->sender('no-reply@gplus.com.ve')
                                    ->addFrom('no-reply@gplus.com.ve')
                                    ->addReplyTo('no-reply@gplus.com.ve')
                                    ->to($user['email'])
                                    ->subject('Aviso de Telecobranza')
                                    ->attach($pdf, sprintf('AvisoTelecobranza%s.pdf', date('YmdHis')))
                                    ->attach(fopen($xlsx, 'r'), $xlsx)
                                    ->html('<h3>Aviso de Telecobranza</h3>');

                                $transport = new GmailSmtpTransport('notificacion@gplus.com.ve', 'W0Phqh7$');
                                
                                // 6) Enviar Correo
                                if ( $transport->send($email) ) {
                                    // 6.1) Borrar XLSX temporal
                                    unlink($xlsx);

                                    // 6.2) Listar Avisos
                                    foreach ($notices as $notice) {
                                        $noticesToUpdate[$notice['sm_notastelecobranza_id']] = $notice;
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
        foreach ($noticesToUpdate as $notice) {
            // 1) Valores
            $message = 'Aviso de Telecobranza de Factura Nro ' . $notice['documentno'];

            // 2) Consola
            $io->note($message);

            // 3) Log
            $this->logger->info($message);
        }
        
        $io->progressFinish();

        if (count($noticesToUpdate) > 0) {
            $io->info('Total: ('. count($noticesToUpdate) .') Avisos');
            $io->success('Avisos de Telecobranza enviados!');
        } else {
            $io->success('No hay avisos por enviar!');
        }

        return 1;
    }

    public function export($products)
    {
        // 1) Preparar XLSX
        $sheet = $this->spreedsheet->getActiveSheet();

        // 1.1) Columnas 
        $sheet->setTitle('Avisos de Telecobranza');
        $sheet->getCell('A1')->setValue('Marca');
        $sheet->getCell('B1')->setValue('Organización');
        $sheet->getCell('C1')->setValue('Tercero');
        $sheet->getCell('D1')->setValue('Tipo de Documento');
        $sheet->getCell('E1')->setValue('Nro de Documento');
        $sheet->getCell('F1')->setValue('Descripción Factura');
        $sheet->getCell('G1')->setValue('Fecha Facturación');
        $sheet->getCell('H1')->setValue('Fecha Vencimiento');
        $sheet->getCell('I1')->setValue('Dias Vencidos');
        $sheet->getCell('J1')->setValue('Saldo Abierto');
        $sheet->getCell('K1')->setValue('Representante Comercial');
        $sheet->getCell('L1')->setValue('Notas');
        $sheet->getCell('M1')->setValue('Último Contacto');
        $sheet->getCell('N1')->setValue('Próximo Contacto');
        $sheet->getCell('O1')->setValue('Operador');

        // 1.2) Filas
        $sheet->fromArray($this->processData($products), null, 'A2', true);
        
        // 1.3) Documento
        $xlsx = new Xlsx($this->spreedsheet);
        $fileName = sprintf('AvisoTelecobranza%s.xlsx', date('YmdHis'));
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
                'Tercero'                   => $product['bpartner'],
                'Tipo Documento'            => $product['doctype'],
                'Nro Documento'             => $product['documentno'],
                'Descripcion Factura'       => $product['description'],
                'Fecha Facturacion'         => $product['dateinvoiced'],
                'Fecha Vencimiento'         => $product['duedate'],
                'Dias Vencidos'             => $product['daysdue'],
                'Saldo Abierto'             => $product['openamt'],
                'Representante Comercial'   => $product['rep_comercial'],
                'Notas'                     => $product['nota'],
                'Ultimo Contacto'           => $product['ultimo_contacto'],
                'Proximo Contacto'          => $product['proximo_contacto'],
                'Operador'                  => $product['operador']
            ];
        }

        return $products;
    }
}
