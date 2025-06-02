<?php

    namespace App\Modules\Alimento;

    interface AlimentoRepositoryInterface{
        public function createAlimento(
            string $nombre,
            float $calorias,
            float $proteinas,
            float $carbohidratos,
            float $grasas,
            string $familia,
            float $agua,
            float $fibra,
            string $categoria
        ): bool;

        public function getAlimentoPorId(int $id_alimento): ?array;

        public function getAlimentoPorName(string $nombre): ?array;

        public function getAlimentosFamilia(string $familia): ?array;

        public function calcularValoresNutricionales(array $alimento, float $cantidad): array;

        public function getAlimentos(): array;
    }
?>