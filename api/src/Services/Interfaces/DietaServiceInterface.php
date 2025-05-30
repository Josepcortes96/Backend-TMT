<?php

namespace App\Services\Interfaces;

/**
 * Interfaz para los servicios relacionados con la gestión de dietas.
 */
interface DietaServiceInterface
{
    /**
     * Crea una nueva dieta.
     *
     * @param array $data
     * @return array
     */
    public function createDieta(array $data): array;

    /**
     * Obtiene todas las dietas.
     *
     * @return array
     */
    public function getAllDietas(): array;

    /**
     * Verifica si una dieta existe por su ID.
     *
     * @param int $id_dieta
     * @return array|null
     */
    public function getDietaById(int $id_dieta): ?array;

    /**
     * Elimina una dieta por su ID.
     *
     * @param int $id_dieta
     * @return bool
     */
    public function deleteDieta(int $id_dieta): bool;

    /**
     * Asocia varias comidas a una dieta.
     *
     * @param int $id_dieta
     * @param array $comidas
     * @return array
     */
    public function asociarComidas(int $id_dieta, array $comidas): array;

    /**
     * Actualiza los macronutrientes de una dieta.
     *
     * @param int $id_dieta
     * @param array $macros
     * @return array
     */
    public function actualizarDieta(int $id_dieta, array $macros): array;
}
