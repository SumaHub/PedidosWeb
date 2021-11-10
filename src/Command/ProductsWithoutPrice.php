<?php

namespace App\Command;

use App\Constant;
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
                                $document = $this->pdf->getOutputFromHtml($html);

                                // 4) Preparar Correo
                                $email = (new Email())
                                    ->sender('no-reply@gplus.com.ve')
                                    ->addFrom('no-reply@gplus.com.ve')
                                    ->addReplyTo('no-reply@gplus.com.ve')
                                    ->to($user['email'])
                                    ->subject('Productos sin precio!')
                                    ->attach($document, sprintf('ProductosSinPrecio%s.pdf', date('YmdHis')))
                                    ->html('<h3>Reporte de Productos sin Precio</h3>');

                                $transport = new GmailSmtpTransport('notificacion@gplus.com.ve', 'W0Phqh7$');
                                
                                // 5) Enviar y listar
                                if ( $transport->send($email) ) {
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

        if (count($productToUpdate) > 0)
            $io->success('Reporte de productos sin precio enviado!');
        else
            $io->success('No hay productos nuevos!');

        return 1;
    }
}
