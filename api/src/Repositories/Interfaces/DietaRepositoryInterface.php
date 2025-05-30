<?php

    namespace App\Repositories\Interfaces;

    interface DietaRepositoryInterface{

        public function create(string $nombre, string $descripcion, int $id_dato): int;
        public function getAll(): array;
        public function getById(int $id): ?array;
        public function exists(int $id): bool;
        public function delete(int $id): bool;
        public function asociarComida(int $id_dieta, int $id_comida): void;
        public function updateMacros(int $id_dieta, float $proteinas, float $grasas, float $carbohidratos): bool;

    }
?>