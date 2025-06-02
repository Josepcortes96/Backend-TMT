<?php

namespace App\Services\Interfaces;

interface DietaServiceInterface
{
    public function crearDietaConMacros(array $datos): int;

    public function actualizarMacros(int $id_dieta, float $proteinas, float $grasas, float $carbohidratos): array;

    public function asociarComidas(int $id_dieta, array $comidas): void;

    public function eliminarDieta(int $id_dieta): bool;

    public function obtenerTodas(): array;

    public function obtenerPorId(int $id_dieta): ?array;

    public function dietaExiste(int $id_dieta): bool;
}
?>

