<?php

namespace App\Centro;

interface CentroServiceInterface {
    public function createCentro(string $nombre, string $direccion, string $telefono, string $nombre_fiscal, string $NIF, string $ciudad, string $codigo_postal, string $pais, string $correo): bool;

    public function updateCentro(int $id, string $nombre, string $direccion, string $telefono, string $nombre_fiscal, string $NIF, string $ciudad, string $codigo_postal, string $pais, string $correo): void;

    public function getCentro(): array;

    public function deleteCentro(int $id): bool;
}

?>