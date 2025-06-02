<?php
    namespace App\Modules\Comida;
    /**
     * Interface para definir los servicios de negocios asociados a la Comida
     */
    interface ComidaServiceInterface {
        public function crearComidasConAlimentos(array $datos): array;
        public function agregarAlimentosAComida(array $datos): array;
    }

?>