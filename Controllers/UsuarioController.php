<?php
namespace Controllers;

use Lib\Pages;
use Services\UsuariosService;
use Models\Validacion;

class UsuarioController {

    private Pages $pagina;
    private UsuariosService $usuariosService;

    public function __construct()
    {
        $this->pagina = new Pages();
        $this->usuariosService = new UsuariosService();
    }

    public function mostrarUsuario($error=null):void {
        if (!$this->sesion_usuario()) {
            return;
        }

        $usuario = $this->usuariosService->obtenerUsuarioPorNombreDeUsuario($_SESSION['username']);

        $nombre = $usuario->getNombre();
        $apellidos = $usuario->getApellidos();
        $email = $usuario->getEmail();
        $username = $usuario->getUsername();
        $rol = $usuario->getRol();

        $data = compact('nombre', 'apellidos', 'email', 'username', 'rol');

        if ($error !== null) {
            $data['error_message'] = $error;
        }

        if ($rol == 'admin'){

            $usuarios = $this->usuariosService->obtenerUsuarios();
            $data['usuarios'] = $usuarios;
        }


    $this->pagina->render("Usuario/mostrarUsuario", $data);
    }

    public function obtenerUsuarios(): array|null{
        return $usuarios = $this->usuariosService->obtenerUsuarios();

    }

    public function actualizarUsuario():void {
        // Recibir datos del formulario
        $username = $_POST['username'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellidos = $_POST['apellidos'] ?? '';
        $email = $_POST['email'] ?? '';
        $nuevoRol = $_POST['rol'] ?? 'usur';

        $usuarioValidado = $this->validarSaneaUsuario($username, $nombre, $apellidos, $email, $nuevoRol);
        if (!$usuarioValidado) {
            return;
        }

        $resultado = $this->usuariosService->actualizarUsuario(
            $usuarioValidado['username'],
            $usuarioValidado['nombre'],
            $usuarioValidado['apellidos'],
            $usuarioValidado['email'],
            $usuarioValidado['rol']
        );
            if ($resultado === null) {
                $this->mostrarUsuario();
            } else {
                $this->mostrarUsuario($resultado);
            }
        }



    public function validarSaneaUsuario($username, $nombre, $apellidos, $email, $rol):array|bool {
        $errores = Validacion::validarDatosUsuario($username, $nombre, $apellidos, $email, $rol);

        if (!empty($errores)) {
            $this->mostrarUsuario($errores);


            return false;
        }

        $usuarioSaneado = Validacion::sanearCamposUsuario($username, $nombre, $apellidos, $email, $rol);


        $usuarioSaneado = Validacion::sanearCamposUsuario($username, $nombre, $apellidos, $email, $rol);

        return $usuarioSaneado;
    }

    public function sesion_usuario(): bool {
        return (new BlogController())->sesion_usuario();
    }

    public function login():void {
        (new BlogController())->login();
    }
}
