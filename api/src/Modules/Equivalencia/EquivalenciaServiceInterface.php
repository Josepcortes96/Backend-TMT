<?php

    namespace App\Services\Interfaces;
    
    interface EquivalenciaServiceInterface{

        public function calcularEquivalencia(int $idAlimento, int $idEquivalente, string $categoria, float $cantidadBase) : array;
    }
?>