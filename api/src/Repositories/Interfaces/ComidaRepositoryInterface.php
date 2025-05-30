<?php

    namespace App\Repositories\Interfaces;
    /**
     * Interface para definir las operaciones basicas sobre Comida.
     */
    interface ComidaRepositoryInterface {
        public function createComida(array $data): int;
        public function getComidaId(int $id): ?array;
        public function asociarAlimento(int $comidaId, int $alimentoId, float $cantidad, array $nutricion): void;
        public function agregarAlimento(int $comidaId, int $alimentoId, float $cantidad, array $nutricion): bool;
        public function actualizarCantidadAlimento(int $comidaId, int $alimentoId, float $cantidad, array $nutricion): bool;
        public function eliminarAlimentoDeComida(int $comidaId, int $alimentoId): bool;
        public function eliminarSuplementoDeComida(int $comidaId, int $suplementoId): bool;
        public function actualizarTotalesComida(int $comidaId): void;
        public function insertarEquivalencia(int $comidaId, int $alimentoId, int $id_equivalente, float $cantidad): void;
        public function insertarEquivalencia1(int $comidaId, int $alimentoId, int $id_equivalente, float $cantidad): void;
        public function insertarEquivalencia3(int $comidaId, int $alimentoId, int $id_equivalente, float $cantidad): void;
    }

?>