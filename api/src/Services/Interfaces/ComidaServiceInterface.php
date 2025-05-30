<?php
    namespace App\Services\Interfaces;

    interface ComidaServiceInterface {
        public function crearComidasConAlimentos(array $datos): array;
        public function agregarAlimentosAComida(array $datos): array;
    }

?>