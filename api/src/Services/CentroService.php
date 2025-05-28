<?php

namespace App\Services;

use App\Repositories\Interfaces\CentroRepositoryInterface;
use App\Services\Interfaces\CentroServiceInterface;
use Exception;

class CentroService implements CentroServiceInterface {

    private CentroRepositoryInterface $centroRepository;

    public function __construct(CentroRepositoryInterface $centroRepository) {
        $this->centroRepository = $centroRepository;
    }

    public function createCentro(string $nombre, string $direccion, string $telefono, string $nombre_fiscal, string $NIF, string $ciudad, string $codigo_postal, string $pais, string $correo): bool {
        return $this->centroRepository->createCentro($nombre, $direccion, $telefono, $nombre_fiscal, $NIF, $ciudad, $codigo_postal, $pais, $correo);
    }

    public function updateCentro(int $id, string $nombre, string $direccion, string $telefono, string $nombre_fiscal, string $NIF, string $ciudad, string $codigo_postal, string $pais, string $correo): void {
        $this->centroRepository->updateCentro($id, $nombre, $direccion, $telefono, $nombre_fiscal, $NIF, $ciudad, $codigo_postal, $pais, $correo);
    }

    public function getCentro(): array {
        return $this->centroRepository->getCentro();
    }

    public function deleteCentro(int $id): bool {
        return $this->centroRepository->deleteCentro($id);
    }
}
?>