<?php

namespace App\Services\Interfaces;

interface DietaServiceInterface
{
    public function crearDietaConMacros(array $datos): int;

    public function actualizarMacros(int $id_dieta, float $proteinas, float $grasas, float $carbohidratos): array;

    public function asociarComidas(int $id_dieta, array $comidas): void;

    public function eliminarDieta(int $id_dieta): bool;

    public function obtenerPorId(int $id_dieta): ?array;

    public function dietaExiste(int $id_dieta): bool;

    public function asignarDietaSegunRol(int $id_dieta, int $id_usuario, string $rol): array;

    public function obtenerDietaConDato(int $id_dieta): array;

    public function obtenerDietasPorUsuario(int $id_usuario): array;

    public function obtenerInformeDieta(int $id_dieta): array;

    public function getUltimaDietaCreada(int $id_usuario): array;


}
?>

