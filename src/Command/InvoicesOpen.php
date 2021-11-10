<?php

namespace App\Command;

use App\Constant;
use App\Jaxon\Invoice;
use App\Jaxon\User;
use Knp\Snappy\Pdf;
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

class InvoicesOpen extends Command
{
    protected static $defaultName = 'app:invoices-open-report:send';

    protected $loader;

    protected $logger;

    protected $nodes = array(
        // 1er Nodo => [Vendedor, Analista de CxC]
        1 =>  [1000093, 1000065],
        // 2do Nodo => [Vendedor, Analista de CxC, Lider de CxC, Gerente de CxC, Gerente de Ventas (Base)]
        2 => [1000093, 1000065, 1000066, 1000004, 1000074],
        // 3er Nodo => [Vendedor, Analista de CxC, Lider de CxC, Gerente de CxC, Gerente de Ventas (Base), Junta Directiva]
        3 => [1000093, 1000065, 1000066, 1000004, 1000074, 1000210]
    );

    protected $pdf;

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

        $this->twig     = new Environment($this->loader, [
            'cache'         => 'templates/cache',
            'autoescape'    => false,
            'debug'         => true
        ]);
        $this->twig->addExtension(new IntlExtension());
    }

    protected function configure() { $this->setDescription('Enviar reporte de facturas vencidas.'); }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io     = new SymfonyStyle($input, $output);
        $invoiceToUpdate = [];

        // Recorre los nodos
        $io->progressStart(count($this->nodes));
        foreach ($this->nodes as $node => $roles) {

            // Recorre los roles
            $io->progressAdvance();
            foreach ($roles as $rol) {

                // Busca los usuarios por rol
                $users = $this->user->getByRole($rol);

                // Verfica la cantidad de usuarios
                if($users->NumRows() > 0){

                    // Recorre los usuarios
                    while($user = $users->fetchRow()) {

                        // Verificar el correo
                        if ( isset($user['email']) && !empty(trim($user['email']))) {

                            // Dias de Vencimiento
                            $DaysDue = $node * 7;

                            // 1) Obtener Facturas
                            $invoices = Invoice::getOpen(0, $user['ad_user_id'], 0, $DaysDue, $node);

                            if ($invoices->numRows() > 0) {
                                // 2) Renderizar Vista
                                $html = $this->twig->render('modules/invoice/invoicesOpen.html', [
                                    'home_path'     => Constant::HOME_PATH,
                                    'title'         => 'Pedidos Web | Facturas',
                                    'version'       => 'Versi&oacute;n 2.0.0',
                                    'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                                    'modulo'        => 'Facturas',
                                    'node'          => $node,
                                    'user'          => $user,
                                    'invoices'      => $invoices
                                ]);

                                // 3) Obtener PDF
                                $document = $this->pdf->getOutputFromHtml($html);

                                // 4) Preparar Correo
                                $email = (new Email())
                                    ->sender('no-reply@gplus.com.ve')
                                    ->addFrom('no-reply@gplus.com.ve')
                                    ->addReplyTo('no-reply@gplus.com.ve')
                                    ->to($user['email'])
                                    ->subject('Analisis de Vencimiento: Aviso Nro ' . $node)
                                    ->attach($document, sprintf('FacturasVencidas%s.pdf', date('YmdHis')))
                                    ->html('<h3>Reporte de Facturas Vencidas</h3>');

                                $transport = new GmailSmtpTransport('notificacion@gplus.com.ve', 'W0Phqh7$');
                                
                                // 5) Enviar y listar
                                if ( $transport->send($email) ) {
                                    foreach ($invoices as $invoice) {
                                        $invoiceToUpdate[$invoice['c_invoice_id']] = $invoice;
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
        foreach ($invoiceToUpdate as $id => $invoice) {
            // 1) Valores
            $notificationNode   = $invoice['notificationnode'] == 3 ? $invoice['notificationnode'] : $invoice['notificationnode'] + 1 ;
            $documentType       = $invoice['doctype'];
            $documentNo         = $invoice['documentno'];
            $message            = 'Nivel de Notificacion Nro: '. $notificationNode .' Documento Vencido: ' . $documentType . ' ' . $documentNo;

            // 2) Consola
            $io->note($message);

            // 3) Log
            $this->logger->info($message);

            // 4) Actualizacion
            Invoice::setNotificationNode($id);
        }
        
        $io->progressFinish();

        if(count($invoiceToUpdate) > 0)
            $io->success('Reporte de facturas vencidas enviado!');
        else
            $io->success('No hay facturas por notificar');

        return 1;
    }
}
