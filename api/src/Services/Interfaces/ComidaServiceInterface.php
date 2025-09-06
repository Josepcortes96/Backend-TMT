<?php
    namespace App\Services\Interfaces;

    interface ComidaServiceInterface {
        public function crearComidasConAlimentos(array $datos): array;
        public function agregarAlimentosAComida(array $datos): array;
        /**
         * Eliminar todos los alimentos de una comida
         *
         * @param int $comidaId
         * @return bool
         */
           public function eliminarComidas(array $comidaIds): bool;
    }

?>