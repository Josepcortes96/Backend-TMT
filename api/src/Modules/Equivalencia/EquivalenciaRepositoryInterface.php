<?php

   
namespace App\Repositories\Interfaces;

/**
 * Interface para el repositorio de equivalencias.
 */
interface EquivalenciaRepositoryInterface{
    /**
     * Obtiene los datos nutricionales de un alimento.
     *
     * @param int $id ID del alimento.
     * @return array|null Datos del alimento o null si no existe.
     */
    public function obtenerAlimentoPorId(int $id): ?array;
}

?>