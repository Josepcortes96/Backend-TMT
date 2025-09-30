<?php

namespace App\Services\Interfaces;

interface PlantillaServiceInterface
{
    public function createPlantilla(?string $nombre, int $id_usuario, int $id_centro, ?string $fecha_creacion = null): int; 
    public function asociarComidas(int $id_plantilla, array $comidas): void; 
    public function eliminarPlantilla(int $id_plantilla): bool;
    public function obtenerPlantillasPorCentro(int $id_centro): array;
    public function obtenerInformePlantilla(int $id_plantilla): array;
}
?>

