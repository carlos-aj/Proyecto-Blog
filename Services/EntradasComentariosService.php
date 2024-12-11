<?php
    namespace Services;

    use Repositories\entradasComentariosRepository;
    
    class EntradasComentariosService{
        private entradasComentariosRepository $repository;
    
        public function __construct() {
            $this->repository = new entradasComentariosRepository();
        }
    
        public function findAll(): ?array {
            return $this->repository->findAll();
        }

        public function findEntradasUser($usuario_id): ?array {
            return $this->repository->findEntradasUser($usuario_id);
        }


        public function buscarEntradas(string $query): ?array {
            return $this->repository->buscarEntradas($query);
        }

        public function insertarEntrada($usuario_id, $categoria_id, $titulo, $descripcion, $fecha) {
            return $this->repository->insertarEntrada($usuario_id, $categoria_id, $titulo, $descripcion, $fecha);
        }
        public function editarEntrada($usuario_id, $categoria_id, $titulo, $descripcion, $fecha, $entrada_id) {
            return $this->repository->editarEntrada($usuario_id, $categoria_id, $titulo, $descripcion, $fecha, $entrada_id);
        }
        public function eliminarEntrada($entrada_id): bool {
            // Llama al método del repositorio para eliminar la entrada
            return $this->repository->eliminarEntrada($entrada_id);
        }

        public function buscarPorCategoria(string $categoria): ?array {
            // Obtener todas las entradas
            return $this->repository->obtenerentradascategoria($categoria);
        }
    }
