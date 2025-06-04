<?php

    
    namespace App\Modules\Equivalencia;

    
    interface EquivalenciaServiceInterface{

        public function calcularEquivalencia(int $idAlimento, int $idEquivalente, string $categoria, float $cantidadBase) : array;
    }
?>