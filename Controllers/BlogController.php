<?php
namespace Controllers;

use Lib\Pages;
use Services\EntradasComentariosService;
use Services\UsuariosService;
use Services\CategoriasService;
use Models\Validacion;

class BlogController {

    private Pages $pagina;
    private EntradasComentariosService $entradasService;
    private UsuariosService $usuariosService;

    private CategoriasService $categoriasService;


    public function __construct()
    {
        $this->pagina = new Pages();
        $this->entradasService = new EntradasComentariosService();

        $this->usuariosService = new UsuariosService();

        $this->categoriasService = new CategoriasService();

    }

    public function obtenerDatosBlog($error=null):array {
        $entradas = $this->entradasService->findAll();

        $noResults = empty($entradas);
        $entradas = $entradas ?? [];

         $categorias = $this->categoriasService->obtenerCategorias();


        $data = ['entradas' => $entradas, 'noResults' => $noResults, 'categorias' => $categorias];

        if ($error) {
            $data['loginError'] = $error;
        }

        return $data;
    }


    public function mostrarBlog($error=null, $usuarioRecordado=null, $errorregistro = null):void {
        $data = $this->obtenerDatosBlog($error);

        $usuarioAutenticado = isset($_SESSION['username']);

        if ($errorregistro !== null) {
            $data['error_registro'] = $errorregistro;
        }

        $data['usuario_autenticado'] = $usuarioAutenticado;

        $this->pagina->render("Blog/mostrarBlog", $data);
    }


    public function buscar():void {
        $searchQuery = isset($_POST['q']) ? $_POST['q'] : '';

        $data = $this->obtenerDatosBlog();

        $entradas = $this->entradasService->buscarEntradas($searchQuery);

        $entradas = $entradas ?? [];


        $data['entradas'] = $entradas;
        $data['searchQuery'] = $searchQuery;
        $data['noResults'] = empty($entradas);


        $this->pagina->render("Blog/mostrarBlog", $data);
    }

    public function buscarPorCategoria():void {
        $selectedCategory = isset($_POST['categoria']) ? $_POST['categoria'] : '';

        $data = $this->obtenerDatosBlog();

        $entradas = $this->entradasService->buscarPorCategoria($selectedCategory);

        if ($entradas === null) {
            $entradas = [];
        }

        if (!empty($selectedCategory)) {
            $entradas = array_filter($entradas, function($entrada) use ($selectedCategory) {
                return $entrada['categoria'] === $selectedCategory;
            });
        }

        $data['entradas'] = $entradas;
        $data['selectedCategory'] = $selectedCategory;
        $data['searchQuery'] = $selectedCategory;


        $data['noResults'] = empty($entradas);

        $this->pagina->render("Blog/mostrarBlog", $data);
    }


    public function registroUsuario():void {
        if (isset($_POST['registro'])) {
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $contrasena = $_POST['contrasena'];
            $rol = 'usur';


            $usuarioSaneado = $this->validarSanear($username, $nombre, $apellidos, $email, $rol);
            if (!$usuarioSaneado) {
                return;
            }

            $nombre = $usuarioSaneado['nombre'];
            $apellidos = $usuarioSaneado['apellidos'];
            $email = $usuarioSaneado['email'];
            $username = $usuarioSaneado['username'];
            $rol = $usuarioSaneado['rol'];

            $usuariosService = new UsuariosService();
            $resultado = $usuariosService->register($nombre, $apellidos, $email, $username, $contrasena, $rol);

            $this->mostrarBlog();

            return;
        }
    }

    public function validarSanear($username, $nombre, $apellidos, $email, $rol):array|bool {
        $errores = Validacion::validarDatosUsuario($username, $nombre, $apellidos, $email, $rol);

        if (!empty($errores)) {
            $this->mostrarBlog(null, null, $errores);
            return false;
        }

        $usuarioSaneado = Validacion::sanearCamposUsuario($username, $nombre, $apellidos, $email, $rol);


        return $usuarioSaneado;
    }

    public function login():void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $error = '';

        if ($username && $password) {
            $user = $this->usuariosService->verificaCredenciales($username, $password);
            if ($user) {
                session_start();
                $_SESSION['username'] = $user->getUsername();


                setcookie("usuario_recordado", $user->getUsername(), time() + (30 * 24 * 60 * 60), "/");


            } else {
                $error = 'Usuario o contraseña incorrecta';
            }
        }

        $usuarioRecordado = isset($_COOKIE['usuario_recordado']) ? $_COOKIE['usuario_recordado'] : null;

        $this->mostrarBlog($error, $usuarioRecordado);
    }


    public function logout():void {
        session_start();
        session_destroy();
        $this->mostrarBlog();
    }

    public function sesion_usuario():bool {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['username'])) {
            $this->login();
            return false;
        }

        return true;
    }

    public function mostrarblogsesion($error=null):void {
        if ($this->sesion_usuario()) {
            $this->mostrarBlog();
        }
    }

}

