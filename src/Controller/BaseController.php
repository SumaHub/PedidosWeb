<?php

namespace App\Controller;

use App\Repository\Main\AdRoleRepository;
use App\Repository\Main\AdWindowAccessRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BaseController extends AbstractController
{
    protected $loader;

    protected $pdf;

    protected $session;

    protected $twig;

    # TODO: Cambiar valores por el del usuario para Web Services
    protected $ws = [
        'client'=>1000001,
        'role' => 1000002,
        'user' => 'DMARQUEZ',
        'pass' => 'T0tt014',
        'stage'=> '9'
    ];

    protected $status = [
        'VO' => [
            'flag' => 'danger',
            'name' => 'anulado'
        ],
        'IN' => [
            'flag' => 'danger',
            'name' => 'invalido'
        ],
        'CL' => [
            'flag' => 'dark',
            'name' => 'cerrado'
        ],
        'CO' => [
            'flag' => 'success',
            'name' => 'completo'
        ],
        'NA' => [
            'flag' => 'danger',
            'name' => 'no aprobado'
        ],
        'AP' => [
            'flag' => 'warning',
            'name' => 'aprobado'
        ],
        'IP' => [
            'flag' => 'warning',
            'name' => 'en progreso'
        ],
        'DR' => [
            'flag' => 'info',
            'name' => 'borrador'
        ]
    ];

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
    public function index(): RedirectResponse
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
    public function VerifySession() { return !is_null($this->session->get('user', null)); }

    /** 
     * Verifica los accesos del rol a la ventana
     * @param string $string
     * @return boolean
     * 
     */
    public function VerifyWindow(ManagerRegistry $manager, Int $windowID = 0, Int $roleID = 0)
    {
        $RWindowAccess = new AdWindowAccessRepository($manager);
        $window = $RWindowAccess->findBy(['ad_window_id'  => $windowID, 'ad_role_id'    => $roleID], null, 1);
        if ( count($window) > 0 )
            return $window[0]->getIsactive();

        $RRole = new AdRoleRepository($manager);
        $role = $RRole->find($roleID);
        $roleIncludes = $role->getAdRoleIncludeds();
        foreach ($roleIncludes as $role) {
            if ( $this->VerifyWindow($manager, $windowID, $role->getIncludedRoleId()) )
                return true;
        }
       
        return false;
    }
}
?>