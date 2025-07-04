<?php

namespace App\Services\Interfaces;

interface DatoServiceInterface
{
    public function crear(array $data): int;

    public function obtenerPorId(int $id_dato): array;

    public function obtenerPorControl(string $control): array;

    public function actualizar(int $id_dato, array $data): bool;

    public function eliminar(int $id_dato): bool;

    public function obtenerTodos(): array;

    public function obtenerPeso(int $id_usuario): float;
}
?>