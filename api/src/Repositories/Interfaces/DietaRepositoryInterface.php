<?php

    namespace App\Repositories\Interfaces;

    interface DietaRepositoryInterface{
        
        public function createDieta(string $nombre, ?string $descripcion, int $id_usuario, int $id_dato,  float $calorias_dieta, float $proteinas_dieta, float $grasas_dieta, float $carbohidratos_dieta, ?string $fecha_creacion = null): int;

        public function getDietaConDato(int $id_dieta): array;

        public function getDietasPorUsuario(int $id_usuario): array;

        public function getDietaById(int $id_dieta): bool;

        public function getDieta(int $id_dieta): ?array;

        public function deleteDieta(int $id_dieta): bool;

        public function asociarComidaDieta(int $id_dieta, int $id_comida): void;

        public function actualizarDieta(int $id_dieta, string $nombre, string $descripcion, float $proteinas_dieta, float $grasas_dieta, float $carbohidratos_dieta): array;
        
        public function insertDietaRol(int $id_dieta, int $id_usuario, string $rol): array;
        
        public function getInformeDieta(int $id_dieta) : array;

        public function getUltimaDietaCreada(int $id_usuario): array;

    
    }

?>

