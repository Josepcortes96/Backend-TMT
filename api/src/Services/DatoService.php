<?php

namespace App\Services;

use App\Repositories\Interfaces\DatoRepositoryInterface;
use App\Services\Interfaces\DatoServiceInterface;


class DatoService implements DatoServiceInterface
{
    private DatoRepositoryInterface $datoRepository;

    public function __construct(DatoRepositoryInterface $datoRepository)
    {
        $this->datoRepository = $datoRepository;
    }

    public function crear(array $data): int
    {
        return $this->datoRepository->createDato($data);
    }

    public function obtenerPorId(int $id_dato): array
    {
        return $this->datoRepository->getDatoById($id_dato);
    }

    public function obtenerPorControl(string $control): array
    {
        return $this->datoRepository->getDatoByControl($control);
    }

    public function actualizar(int $id_dato, array $data): bool
    {
        return $this->datoRepository->actualizarDato($id_dato, $data);
    }

    public function eliminar(int $id_dato): bool
    {
        return $this->datoRepository->deleteDato($id_dato);
    }

    public function obtenerTodos(): array
    {
        return $this->datoRepository->getAll();
    }

    public function obtenerPeso(int $id_usuario): float
    {
        return $this->datoRepository->getPeso($id_usuario);
    }
}
?>