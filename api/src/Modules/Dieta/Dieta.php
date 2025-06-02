<?php

    namespace App\Modules\Dieta;

    class Dieta
    {
        public ?int $id_dieta = null;
        public int $id_usuario;
        public int $id_dato;
        public string $nombre;
        public ?string $descripcion;
        public string $fecha_creacion;
        public ?float $calorias_dieta;
        public ?float $proteinas_dieta;
        public ?float $grasas_dieta;
        public ?float $carbohidratos_dieta;
        public ?string $observaciones;
        public ?string $fecha_inicio;

        public function __construct(
            int $id_usuario,
            int $id_dato,
            string $nombre,
            ?string $descripcion = null,
            ?string $fecha_creacion = null
        ) {
            $this->id_usuario = $id_usuario;
            $this->id_dato = $id_dato;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->fecha_creacion = $fecha_creacion ?? date('Y-m-d');
        }
    }

?>
