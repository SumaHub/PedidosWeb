<?php

namespace App\Controller;

use App\Util;
use Jaxon\AjaxBundle\Jaxon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseController extends AbstractController
{
    protected $loader;

    protected $session;

    protected $twig;

    public function __construct()
    {
        $this->session = new Session();

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

    /**
     * Funcion para enrutar al area de trabajo
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Vista
     */
    public function login(): RedirectResponse { return $this->redirectToRoute('dashboard'); }

    /**
     * Ruta raiz - corroborar la sesion del usuario
     * @Route("/", name="index")
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Vista
     */
    public function index(Jaxon $jaxon): RedirectResponse
    { 
        return !$this->VerifySession() ? $this->logout() :  $this->login(); 
    }

    /**
     * Funcion para enrutar la salida del sistema
     * @Route("/salir", name="salir")
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Vista
     */
    public function logout(): RedirectResponse { return $this->redirectToRoute('ingresar'); }

    /** 
     * Verifica las variables de sesion y la memoria cache del cliente
     * @param string $string
     * @return boolean
     */
    public function VerifySession(){
        return !is_null($this->session->get('user', null));
    }
}
?>