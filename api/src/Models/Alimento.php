<?php

    namespace App\Models;

    class Alimento{
        public string $nombre;
        public int $calorias;
        public float $proteinas;
        public float $carbohidratos;
        public float $grasas;
        public float $fibra;
        public string $familia;
        public float $agua;
        public string $categoria;
      

        public function __construct(array $data) {
            $this->nombre = $data['nombre'];
            $this->calorias = $data['calorias'];
            $this->proteinas = $data['proteinas'];
            $this->carbohidratos = $data['carbohidratos'];
            $this->grasas = $data['grasas'];
            $this->fibra = $data['fibra'];
            $this->familia = $data['familia'];
            $this->agua = $data['agua'];
            $this->categoria = $data['categoria'];
        }
    }
?>