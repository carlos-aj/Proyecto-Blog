<?php
    namespace Services;
    use Repositories\UsuariosRepository;
    use Models\Usuarios;
    class UsuariosService{
        private UsuariosRepository $userRepository;
        function __construct() {
            $this->userRepository = new UsuariosRepository();
        }
       
        public function register($nombre, $apellidos, $email, $username, $contrasena, $rol): ?string {
            return $this->userRepository->registro($nombre, $apellidos, $email, $username, $contrasena, $rol);
        }

        public function verificaCredenciales(string $username, string $password): ?Usuarios {
            $user = $this->userRepository->findByUsername($username);
            
            if ($user && password_verify($password, $user->getContrasena())) {
                return $user;
            } else {
                return null;
            }
        }

        public function obtenerUsuarioPorNombreDeUsuario(string $username): ?Usuarios {
            return $this->userRepository->findByUsername($username);
        }


        public function actualizarUsuario(string $username, string $nombre, string $apellidos, string $email, string $nuevoRol): ?string {
            return $this->userRepository->actualizarUsuario($username, $nombre, $apellidos, $email, $nuevoRol);
        }

        public function obtenerUsuarios(): ?array {
            return $this->userRepository->obtenerUsuarios();
        }
        
        
    }