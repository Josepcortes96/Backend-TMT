<?php

    namespace App\Models;

    class Comida {
        public int $id_comida;
        public string $tipo_comida;
        public string $hora;
        public int $calorias_totales_comida = 0;
        public int $proteinas_totales_comida = 0;
        public int $carbohidratos_totales_comida = 0;
        public int $grasas_totales_comida = 0;
        public ?string $notas;

        public function __construct(array $data) {
            $this->tipo_comida = $data['tipo_comida'];
            $this->hora = $data['hora'];
            $this->notas = $data['notas'] ?? null;
        }
    }
?>