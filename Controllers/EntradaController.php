<?php

namespace Controllers;

use Lib\Pages;
use Services\UsuariosService;
use Services\EntradasComentariosService;
use Services\CategoriasService;
use Models\Validacion;

class EntradaController {

    private Pages $pagina;
    private UsuariosService $usuariosService;
    private EntradasComentariosService $entradasService;
    private CategoriasService $categoriasService;

    public function __construct()
    {
        $this->pagina = new Pages();
        $this->usuariosService = new UsuariosService();
        $this->entradasService = new EntradasComentariosService();
        $this->categoriasService = new CategoriasService();
    }

    public function mostrarEntradas($error=null):void {
        if (!$this->sesion_usuario()) {
            return;
        }
        $usuario = $this->usuariosService->obtenerUsuarioPorNombreDeUsuario($_SESSION['username']);
        $usuario_id = $usuario->getId();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['categoria'])) {

            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $categoria_id = $_POST['categoria'];
            $fecha = date('Y-m-d');

            if (!$this->validasanea($titulo, $descripcion, $categoria_id, $fecha)) {
                return;
            }

            if (isset($_POST['entrada_id'])) {
                $this->entradasService->editarEntrada($usuario_id, $categoria_id, $titulo, $descripcion, $fecha, $_POST['entrada_id']);
            } else {
                $this->entradasService->insertarEntrada($usuario_id, $categoria_id, $titulo, $descripcion, $fecha);
            }
        }

        $data = $this->obtenerDatosEntradas($error, $usuario_id);

        $this->pagina->render("Blog/mostrarEntradas", $data);
    }

    private function validasanea(&$titulo, &$descripcion, &$categoria_id, &$fecha):bool {
        $errores = Validacion::validar($titulo, $descripcion, $categoria_id, $fecha);
        if (!empty($errores)) {
            $error_message = "Los campos están vacíos.";

            $data = $this->obtenerDatosEntradas();

            $data['error_message'] = $error_message;

            $this->pagina->render("Blog/mostrarEntradas", $data);

            return false;
        }

        $campos_saneados = Validacion::sanearCampos($titulo, $descripcion, $categoria_id, $fecha);
        $titulo = $campos_saneados['titulo'];
        $descripcion = $campos_saneados['descripcion'];
        $categoria_id = $campos_saneados['categoria'];
        $fecha = $campos_saneados['fecha'];

        return true;
    }

    public function obtenerDatosEntradas($error=null, $usuario_id=null):array {
        $entradas = $this->entradasService->findEntradasUser($usuario_id);

        $noResults = empty($entradas);

        $categorias = $this->categoriasService->obtenerCategorias();

        $data = [
            'categorias' => $categorias,
            'entradas' => $entradas,
            'noResults' => $noResults
        ];

        if ($error) {
            $data['loginError'] = $error;
        }

        return $data;
    }
    public function eliminarEntrada():void {
        if (isset($_POST['entrada_id'])) {
            $entrada_id = $_POST['entrada_id'];

            $resultado = $this->entradasService->eliminarEntrada($entrada_id);

            $this->mostrarEntradas();
        } else {
            echo "ID de entrada no válido";
        }
    }

    private function sesion_usuario(): bool {
        return (new BlogController())->sesion_usuario();
    }

    private function login():void {
        (new BlogController())->login();
    }
}
