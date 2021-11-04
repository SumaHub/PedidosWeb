<?php

namespace App\Command;

use App\Controller\MailerController;
use App\Jaxon\Product;
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
use Twig\Loader\FilesystemLoader;

class NewProduct extends Command
{
    protected static $defaultName = 'app:new-product-report:send';

    protected $loader;

    protected $logger;

    protected $nodes = array(
        // 1er Nodo => [Junta Directiva]
        1 =>  [1000210]
    );

    protected $mailer;

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
            'orientation' => 'Landscape',
            'enable-local-file-access' => true
        ]);
        $this->pdf->setTemporaryFolder(dirname(__DIR__) . '/../public/tmp/');
        
        $this->twig     = new Environment($this->loader, [
            'cache'         => 'templates/cache',
            'autoescape'    => false,
            'debug'         => false
        ]);
    }

    protected function configure()
    {
        $this
            ->setDescription('Enviar reporte de productos nuevos.')
        ;
    }

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

                // Recorre los usuarios
                $users = $this->user->getByRole($rol);
                while($user = $users->fetchRow()){

                    // Obtener Facturas
                    $products = Product::getWithoutPrice();

                    if ($products->numRows() > 0) {
                        // Renderizar Vista
                        $html = $this->twig->render('modules/product/newProducts.html', [
                            'title'         => 'Pedidos Web | Productos',
                            'version'       => 'Versi&oacute;n 2.0.0',
                            'bodyClass'     => 'hold-transition sidebar-mini layout-fixed',
                            'modulo'        => 'Productos',
                            'products'      => $products
                        ]);

                        // Obtener PDF
                        $document = $this->pdf->getOutputFromHtml($html);

                        // Preparar Correo
                        $email = (new Email())
                            ->sender('no-reply@gplus.com.ve')
                            ->addFrom('no-reply@gplus.com.ve')
                            ->addReplyTo('no-reply@gplus.com.ve')
                            ->to($user['email'])
                            ->subject('Productos sin precio!')
                            ->attach($document, sprintf('ProductosSinPrecio%s.pdf', date('YmdHis')))
                            ->html('<h3>Reporte de Productos sin Precio</h3>');

                        $transport = new GmailSmtpTransport('notificacion@gplus.com.ve', 'W0Phqh7$');
                        //$transport = new MailerController('notificacion@gplus.com.ve', 'W0Phqh7$');
                        
                        if ( $transport->send($email) ) {
                            foreach ($products as $product) {
                                $productToUpdate[$product['m_product_id']] = $product;
                            }
                        } 
                    }
                }
            }
        }

        // Actualiza los registro
        foreach ($productToUpdate as $id => $product) {
            // 1) Valores
            $message = 'Producto Nuevo: ' . $product['value'] . ' en Orden Nro ' . $product['documentno'];

            // 2) Consola
            $io->note($message);

            // 3) Log
            $this->logger->info($message);
        }
        
        $io->progressFinish();

	if (count($productToUpdate) > 0)
        $io->success('Reporte de productos sin precio enviado!');
	else
		$io->success('No hay productos nuevos!');

        return 1;
    }
}
