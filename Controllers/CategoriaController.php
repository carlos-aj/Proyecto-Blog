<?php

namespace Controllers;

use Lib\Pages;
use Services\CategoriasService;
use Services\UsuariosService;

class CategoriaController {

    private Pages $pagina;
    private CategoriasService $categoriasService;
    private UsuariosService $usuariosService;

    public function __construct()
    {
        $this->pagina = new Pages();
        $this->categoriasService = new CategoriasService();
        $this->usuariosService = new UsuariosService();
    }

    public function mostrarCategorias($error = null):void {
        if (!$this->sesion_usuario()) {
            return;
        }

        $usuario = $this->usuariosService->obtenerUsuarioPorNombreDeUsuario($_SESSION['username']);
        $rolUsuario = $usuario->getRol();

        $esAdmin = $rolUsuario === 'admin' ? true : false;

        $categorias = $this->categoriasService->obtenerCategorias();

        $this->pagina->render("Blog/mostrarCategorias", [
            'categorias' => $categorias,
            'esAdmin' => $esAdmin,
            'mensaje' => $error
        ]);
    }

    public function registroCategoria():void {
        $mensaje = '';

        if ($this->sesion_usuario()) {
            $usuario = $this->usuariosService->obtenerUsuarioPorNombreDeUsuario($_SESSION['username']);

            if ($usuario->getRol() === 'admin') {
                if (isset($_POST['nueva_categoria'])) {
                    $nombreCategoria = $_POST['nueva_categoria'];
                    $this->categoriasService->guardarCategoria($nombreCategoria);
                }
            } else {
                $mensaje = "No tienes permisos de administrador para registrar nuevas categorías.";
            }
        }

        $this->mostrarCategorias();
    }

    private function sesion_usuario(): bool {
        return (new BlogController())->sesion_usuario();
    }

    private function login():void {
        (new BlogController())->login();
    }
}
