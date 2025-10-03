<?php

namespace App\Services\Interfaces;

interface DatoServiceInterface
{
    public function crear(array $data): int;

    public function obtenerPorId(int $id_dato): array;

    public function getDatoByNombre(string $nombre, int $idUsuario): array;

    public function actualizar(int $id_dato, array $data): bool;

    public function eliminar(int $id_dato): bool;

    public function obtenerTodos(): array;

    public function obtenerPeso(int $id_usuario): float;

    public function getUltimosControles(int $idUsuario): array;

    public function getTodosControles(int $idUsuario): array;

    public function getUltimoControlPorId(int $idUsuario): ?array;

    public function getInformeDato(int $idUsuario, int $idDato): ?array;
    
    public function getInformeDatoHistorico(int $idUsuario): ?array;
}
?>