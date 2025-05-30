<?php

namespace App\Services;

use App\Repositories\Interfaces\DietaRepositoryInterface;
use App\Services\Interfaces\DietaServiceInterface;

class DietaService implements DietaServiceInterface
{
    public function __construct(private DietaRepositoryInterface $repo) {}

    public function createDieta(array $data): array
    {
        $id = $this->repo->create($data['nombre'], $data['descripcion'], $data['id_dato']);
        return ["id_dieta" => $id];
    }

    public function getAllDietas(): array
    {
        return $this->repo->getAll();
    }

    public function getDietaById(int $id_dieta): ?array
    {
        return $this->repo->getById($id_dieta);
    }

    public function deleteDieta(int $id_dieta): bool
    {
        return $this->repo->delete($id_dieta);
    }

    public function asociarComidas(int $id_dieta, array $comidas): array
    {
        if (!$this->repo->exists($id_dieta)) {
            throw new \Exception("La dieta no existe.");
        }

        foreach ($comidas as $comida) {
            if (!isset($comida['id_comida'])) {
                throw new \Exception("Comida inválida.");
            }
            $this->repo->asociarComida($id_dieta, (int)$comida['id_comida']);
        }

        return ["message" => "Comidas asociadas correctamente"];
    }

    public function actualizarDieta(int $id_dieta, array $macros): array
    {
        $ok = $this->repo->updateMacros(
            $id_dieta,
            $macros['proteinas_dieta'],
            $macros['grasas_dieta'],
            $macros['carbohidratos_dieta']
        );

        return $ok
            ? ["message" => "Dieta actualizada correctamente"]
            : ["error" => "No se pudo actualizar la dieta"];
    }
}
?>