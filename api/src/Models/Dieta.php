<?php

    namespace App\Models;

    class Dieta
    {
        public function __construct(
            public ?int $id_dieta,
            public string $nombre,
            public string $descripcion,
            public ?string $fecha_creacion = null,
            public ?int $id_dato = null,
            public ?float $calorias_dieta = null,
            public ?float $proteinas_dieta = null,
            public ?float $grasas_dieta = null,
            public ?float $carbohidratos_dieta = null,
            public ?string $observaciones = null,
            public ?string $fecha_inicio = null
        ) {}
    }

    /**
     * Falta corregir la creacion de dieta, ya que viene de forma dinamica.
     * Primero crear los valores nutricionales, desde el frontend y se envia con un post a la tabla datos. Despues directamente se coje los valores con un get de la tabla datos para trabajar con ellos.
     * Se introducen los valores nutricionales a la dieta.
     * Flujo Datos-> Dieta-> Comida/Alimento.
     *PENDIENTE 
     */
?>