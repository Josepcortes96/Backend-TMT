<?php

    namespace App\Repositories\Interfaces;

    interface PlantillaRepositoryInterface{
        
        public function createPlantilla(?string $nombre,  int $id_usuario, int $id_centro, ?string $fecha_creacion = null): int;
        public function getPlantilla(int $id_plantilla):array; 
        public function deletePlantilla(int $id_plantilla):bool;
        public function asociarComidaPlantilla(int $id_plantilla, int $id_comida):void;
        public function getPlantillaPorCentro(int $id_centro): array;
    }

?>

