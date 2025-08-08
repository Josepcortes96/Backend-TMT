<?php

namespace App\Model;

class Dieta
{
    public ?int $id_dieta = null;
    public int $id_usuario;
    public int $id_dato;
    public ?string $nombre;
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
        ?string $nombre = null,
        ?string $descripcion = null,
        ?float $calorias_dieta = null,
        ?float $proteinas_dieta = null,
        ?float $grasas_dieta = null,
        ?float $carbohidratos_dieta = null,
        ?string $fecha_creacion = null,
        ?string $observaciones = null,
        ?string $fecha_inicio = null
    ) {
        $this->id_usuario = $id_usuario;
        $this->id_dato = $id_dato;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->calorias_dieta = $calorias_dieta;
        $this->proteinas_dieta = $proteinas_dieta;
        $this->grasas_dieta = $grasas_dieta;
        $this->carbohidratos_dieta = $carbohidratos_dieta;
        $this->fecha_creacion = $fecha_creacion ?? date('Y-m-d');
        $this->observaciones = $observaciones;
        $this->fecha_inicio = $fecha_inicio;
    }
}
?>