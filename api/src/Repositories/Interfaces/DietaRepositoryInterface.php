<?php

    namespace App\Repositories\Interfaces;

    interface DietaRepositoryInterface{
        public function createDieta(string $nombre, ?string $descripcion, int $id_usuario, int $id_dato,  float $calorias_dieta, float $proteinas_dieta, float $grasas_dieta, float $carbohidratos_dieta, ?string $fecha_creacion = null): int;

        public function getDietas(): array;

        public function getDietaById(int $id_dieta): bool;

        public function getDieta(int $id_dieta): ?array;

        public function deleteDieta(int $id_dieta): bool;

        public function asociarComidaDieta(int $id_dieta, int $id_comida): void;

        public function actualizarDieta(int $id_dieta, float $proteinas_dieta, float $grasas_dieta, float $carbohidratos_dieta): array;
    }

?>

