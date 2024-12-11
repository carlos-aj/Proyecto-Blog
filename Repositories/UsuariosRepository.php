<?php
    namespace Repositories;
    use Lib\DataBase;
    use Models\Usuarios;
    use DateTime;
    use PDOException;
    use PDO;
    class UsuariosRepository{
        private DataBase $conexion;
        private mixed $sql;
        function __construct(){
            $this->conexion = new DataBase();
        }

        public function registro($nombre, $apellidos, $email, $username, $contrasena, $rol): ?string {
            try {
                $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
                $this->sql = $this->conexion->prepareSQL("INSERT INTO usuarios (nombre, apellidos, email, username, contrasena, rol) VALUES (:nombre, :apellidos, :email, :username, :contrasena, :rol);");
                $this->sql->bindValue(":nombre", "$nombre", PDO::PARAM_STR);
                $this->sql->bindValue(":apellidos", $apellidos, PDO::PARAM_STR);
                $this->sql->bindValue(":email", $email, PDO::PARAM_STR);
                $this->sql->bindValue(":username", $username, PDO::PARAM_STR);
                $this->sql->bindValue(":contrasena", $contrasena_cifrada, PDO::PARAM_STR);
                $this->sql->bindValue(":rol", $rol, PDO::PARAM_STR);
                $this->sql->execute();
                $this->sql->closeCursor();
                $resultado = null;
            } catch (PDOException $e) {
                $resultado = $e->getMessage();
            }
            return $resultado;
        }

        public function findByUsername(string $username): ?Usuarios {
            try {
                $this->sql = $this->conexion->prepareSQL("SELECT * FROM usuarios WHERE username = :username LIMIT 1;");
                $this->sql->bindValue(":username", $username, PDO::PARAM_STR);
                $this->sql->execute();
                $usuarioData = $this->sql->fetch(PDO::FETCH_ASSOC);

                $this->sql->closeCursor();

                if ($usuarioData) {
                    $usuario = new Usuarios(
                        $usuarioData['id'],
                        $usuarioData['nombre'],
                        $usuarioData['apellidos'],
                        $usuarioData['email'],
                        $usuarioData['username'],
                        $usuarioData['contrasena'],
                        $usuarioData['rol']
                    );
                    return $usuario;
                } else {
                    return null;
                }
            } catch (PDOException $e) {
                return null;
            }
        }


        public function obtenerUsuarios() {
            $usuarioData = null;
            try {
                $this->sql = $this->conexion->prepareSQL("SELECT * FROM usuarios");

                $this->sql->execute();
                $usuarioData = $this->sql->fetchAll(PDO::FETCH_ASSOC);
                $this->sql->closeCursor();
                $usuarioCommit = $usuarioData ?: null;

            } catch (PDOException $e) {
                $usuarioCommit = $e->getMessage();
            }

            return $usuarioCommit;
        }

        public function actualizarUsuario(string $username, string $nombre, string $apellidos, string $email, string $nuevoRol): ?string {
            try {
                $this->sql = $this->conexion->prepareSQL("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, email = :email, rol = :rol WHERE username = :username");
                $this->sql->bindValue(":username", $username, PDO::PARAM_STR);
                $this->sql->bindValue(":nombre", $nombre, PDO::PARAM_STR);
                $this->sql->bindValue(":apellidos", $apellidos, PDO::PARAM_STR);
                $this->sql->bindValue(":email", $email, PDO::PARAM_STR);
                $this->sql->bindValue(":rol", $nuevoRol, PDO::PARAM_STR);
                $this->sql->execute();
                $this->sql->closeCursor();
                return null;
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }

    }