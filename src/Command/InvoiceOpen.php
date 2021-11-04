<?php

namespace App\Command;

use App\Jaxon\Invoice;
use App\Jaxon\User;
use Knp\Snappy\Pdf;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class InvoiceOpen extends Command
{
    protected static $defaultName = 'app:invoice-open-report:send';

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

    public function __construct(User $user, MailerInterface $mailer, LoggerInterface $logger)
    {
        parent::__construct(null);
        
        $this->user     = $user;
        $this->loader   = new FilesystemLoader('templates');
        $this->logger   = $logger;
        $this->mailer   = $mailer;

        // PDF options
        $this->pdf      = new Pdf('/usr/local/bin/wkhtmltopdf', [
            'orientation' => 'Landscape',
            'enable-local-file-access' => true
        ]);
        $this->pdf->setTemporaryFolder(dirname(__DIR__) . '/../public/tmp/');

        $this->twig     = new Environment($this->loader, [
            'cache'         => 'templates/cache',
            'autoescape'    => false,
            'debug'         => true
        ]);
    }

    protected function configure()
    {
        $this
            ->setDescription('Enviar reporte de facturas vencidas.')
        ;
    }

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

                // Recorre los usuarios
                $users = $this->user->getByRole($rol);
                while($user = $users->fetchRow()){

                    // Dias de Vencimiento
                    $DaysDue = $node * 7;

                    // Obtener Facturas
                    $invoices = Invoice::getOpen(0, $user['ad_user_id'], 0, $DaysDue, $node);

                    if ($invoices->numRows() > 0) {
                        // Renderizar Vista
                        $html = $this->twig->render('modules/invoice/invoiceOpen.html', [
                            'title'         => 'Pedidos Web | Facturas',
                            'version'       => 'Versi&oacute;n 2.0.0',
                            'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                            'modulo'        => 'Facturas',
                            'invoices'      => $invoices
                        ]);

                        // Obtener PDF
                        $document = $this->pdf->getOutputFromHtml($html);

                        // Preparar Correo
                        $email = (new TemplatedEmail())
                            ->from('davidmarsant@gmail.com')
                            ->to($user['email'])
                            ->subject('Analisis de Vencimiento: Aviso Nro ' . $node)
                            ->attach($document, sprintf('FacturasVencidas%s.pdf', date('YmdHis')))
                            ->html('<h3>Reporte de Facturas Vencidas</h3>');

                        $transport = new GmailSmtpTransport('notificacion@gplus.com.ve', 'W0Phqh7$');
                        
                        if ( $transport->send($email) ) {
                            foreach ($invoices as $invoice) {
                                $invoiceToUpdate[$invoice['c_invoice_id']] = $invoice;
                            }
                        } 
                    }
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

        $io->success('Reporte de facturas vencidas enviado!');

        return 1;
    }
}
