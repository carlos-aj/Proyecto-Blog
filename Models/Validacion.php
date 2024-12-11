<?php

namespace Models;
use DateTime;

class Validacion {

    public static function validar($titulo, $descripcion, $categoria, $fecha) {
        $errores = [];

        if (empty($titulo) || trim($titulo) === '') {
            $errores['titulo'] = "Es obligatorio introducir el título.";
        }

        if (empty($descripcion) || trim($descripcion) === '') {
            $errores['descripcion'] = "Es obligatorio introducir la descripción.";
        }

        if (empty($categoria) || trim($categoria) === '') {
            $errores['categoria'] = "Es obligatorio introducir la categoría.";
        }

        if (empty($fecha) || !strtotime($fecha)) {
            $errores['fecha'] = "La fecha no es válida.";
        }

        return $errores;
    }

    public static function sanearCampos($titulo, $descripcion, $categoria, $fecha): array {
        $titulo = trim($titulo);
        $descripcion = trim($descripcion);
        $categoria = trim($categoria);
        $fecha = trim($fecha);

        $titulo = self::sanearString($titulo);

        $descripcion = self::sanearString($descripcion);

        $categoria = self::sanearString($categoria);

        $fecha = self::sanearFecha($fecha);

        return ['titulo' => $titulo, 'descripcion' => $descripcion, 'categoria' => $categoria, 'fecha' => $fecha];
    }


    public static function validarDatosUsuario($username, $nombre, $apellidos, $email, $rol) {
        $errores = [];

        if (empty($username) || trim($username) === '') {
            $errores['username'] = "El nombre de usuario es obligatorio.";
        }

        if (empty($nombre) || trim($nombre) === '') {
            $errores['nombre'] = "El nombre es obligatorio.";
        }

        if (empty($apellidos) || trim($apellidos) === '') {
            $errores['apellidos'] = "Los apellidos son obligatorios.";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = "El email no es válido.";
        }

        if (empty($rol) || !in_array($rol, ['admin', 'usur'])) {
            $errores['rol'] = "El rol no es válido.";
        }

        return $errores;
    }

    public static function sanearCamposUsuario($username, $nombre, $apellidos, $email, $rol): array {
        $username = trim($username);
        $nombre = trim($nombre);
        $apellidos = trim($apellidos);
        $email = trim($email);
        $rol = trim($rol);

        $username = self::sanearString($username);

        $nombre = self::sanearString($nombre);

        $apellidos = self::sanearString($apellidos);

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);


        return ['username' => $username, 'nombre' => $nombre, 'apellidos' => $apellidos, 'email' => $email, 'rol' => $rol];
    }

    public static function sanearString(string $texto): string {
        return preg_replace('/[^A-Za-z0-9\sáéíóúÁÉÍÓÚñÑÁÉÍÓÚáéíóú,.:]+/u', '', $texto);
    }


    public static function sanearFecha($fecha): ?string {
        if (preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/', $fecha, $matches)) {
            $dia = $matches[1];
            $mes = $matches[2];
            $anio = $matches[3];

            if (checkdate($mes, $dia, $anio)) {
                $timestamp = strtotime("$anio-$mes-$dia");
                $fecha_parseada = new DateTime();
                $fecha_parseada->setTimestamp($timestamp);

                return $fecha_parseada->format('d-m-Y');
            }
        }

        return null;
    }
}