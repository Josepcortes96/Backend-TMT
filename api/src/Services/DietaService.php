<?php

namespace App\Services;

use App\Services\Interfaces\DietaServiceInterface;

use App\Repositories\Interfaces\DietaRepositoryInterface;
use App\Repositories\Interfaces\ComidaRepositoryInterface;
use Exception;

class DietaService implements DietaServiceInterface
{
    private DietaRepositoryInterface $dietaRepository;
    private ComidaRepositoryInterface $comidaRepository;

    public function __construct(
        DietaRepositoryInterface $dietaRepository,
        ComidaRepositoryInterface $comidaRepository
    ) {
        $this->dietaRepository = $dietaRepository;
        $this->comidaRepository = $comidaRepository;
    }

    public function crearDietaConMacros(array $datos): int
    {
        if (
            empty($datos['nombre']) ||
            !isset($datos['descripcion']) ||
            empty($datos['id_usuario']) ||
            empty($datos['id_dato']) ||
            !isset($datos['proteinas_dieta']) ||
            !isset($datos['grasas_dieta']) ||
            !isset($datos['carbohidratos_dieta'])
        ) {
            throw new Exception("Faltan datos obligatorios para crear la dieta.");
        }

        return $this->dietaRepository->createDieta(
            $datos['nombre'],
            $datos['descripcion'],
            $datos['id_usuario'],
            $datos['id_dato'],
            $datos['proteinas_dieta'],
            $datos['grasas_dieta'],
            $datos['carbohidratos_dieta'],
            $datos['fecha_creacion'] ?? null
        );
    }

    public function actualizarMacros(int $id_dieta, float $proteinas, float $grasas, float $carbohidratos): array
    {
        return $this->dietaRepository->actualizarDieta($id_dieta, $proteinas, $grasas, $carbohidratos);
    }

    public function asociarComidas(int $id_dieta, array $comidas): void
    {
        if (!$this->dietaRepository->getDietaById($id_dieta)) {
            throw new Exception("La dieta con ID $id_dieta no existe.");
        }

        foreach ($comidas as $comida) {
            $id_comida = is_array($comida) ? $comida['id_comida'] ?? null : $comida;

            if (!$id_comida || !$this->comidaRepository->getComidaId($id_comida)) {
                throw new Exception("La comida con ID $id_comida no existe.");
            }

            $this->dietaRepository->asociarComidaDieta($id_dieta, (int)$id_comida);
        }
    }

    public function eliminarDieta(int $id_dieta): bool
    {
        return $this->dietaRepository->deleteDieta($id_dieta);
    }

    public function obtenerTodas(): array
    {
        return $this->dietaRepository->getDietas();
    }

    public function obtenerPorId(int $id_dieta): ?array
    {
        return $this->dietaRepository->getDieta($id_dieta);
    }

    public function dietaExiste(int $id_dieta): bool
    {
        return $this->dietaRepository->getDietaById($id_dieta);
    }
}

?>
