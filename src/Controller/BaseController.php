<?php

namespace App\Controller;

@session_start();

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseController extends AbstractController
{
    protected $loader;

    protected $twig;

    public function __construct()
    {
        $this->loader   = new FilesystemLoader('../templates');
        $this->twig     = new Environment($this->loader, [
            'cache'         => '../templates/cache',
            'autoescape'    => false,
            'debug'         => true
        ]);


        $base64     = new \Twig\TwigFilter('base64', 'base64_encode');
        $num_format  = new \Twig\TwigFunction('number_format', 'number_format');
        $this->twig->addFilter($base64);
        $this->twig->addFunction($num_format);
    }

    public function logout(): RedirectResponse { return $this->redirectToRoute('ingresar'); }
}
?>