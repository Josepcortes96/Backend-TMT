<?php

namespace App\Services;

use App\Services\Interfaces\PlantillaServiceInterface;
use App\Repositories\Interfaces\PlantillaRepositoryInterface;
use App\Repositories\Interfaces\ComidaRepositoryInterface;
use Exception;

class PlantillaService implements PlantillaServiceInterface
{
    private PlantillaRepositoryInterface $plantillaRepository;
    private ComidaRepositoryInterface $comidaRepository;

    public function __construct(
        PlantillaRepositoryInterface $plantillaRepository,
        ComidaRepositoryInterface $comidaRepository
    ) {
        $this->plantillaRepository = $plantillaRepository;
        $this->comidaRepository = $comidaRepository;
    }

    /**
     * Crea una nueva plantilla 
     */
    public function createPlantilla(?string $nombre, int $id_usuario, int $id_centro, ?string $fecha_creacion = null): int {
        return $this->plantillaRepository->createPlantilla($nombre, $id_usuario, $id_centro, $fecha_creacion);
    }

    /**
     * Asocia comidas a una plantilla
     */
    public function asociarComidas(int $id_plantilla, array $comidas): void {
        if (!$this->plantillaRepository->getPlantilla($id_plantilla)) {
            throw new Exception("La plantilla con ID $id_plantilla no existe.");
        }

        foreach ($comidas as $comida) {
            $id_comida = is_array($comida) ? ($comida['id_comida'] ?? null) : $comida;
            $id_comida = (int) $id_comida;

            if (!$id_comida || !$this->comidaRepository->getComidaId($id_comida)) {
                throw new Exception("La comida con ID $id_comida no existe.");
            }

            $this->plantillaRepository->asociarComidaPlantilla($id_plantilla, $id_comida);
        }
    }

    /**
     * Elimina una plantilla por su ID.
     */
    public function eliminarPlantilla(int $id_plantilla): bool {
        return $this->plantillaRepository->deletePlantilla($id_plantilla);
    }

    /**
     * Obtiene todas las plantillas de un centro.
     */
    public function obtenerPlantillasPorCentro(int $id_centro): array {
        return $this->plantillaRepository->getPlantillaPorCentro($id_centro);
    }


    public function obtenerInformePlantilla(int $id_plantilla): array{
       
        $informe = $this->plantillaRepository->getInformePlantilla($id_plantilla);

        return $informe;
    }

}

?>