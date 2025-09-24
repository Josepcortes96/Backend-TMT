<?php

namespace App\Model;

class Plantilla
{
    public ?int $id_plantilla = null;
    public int $id_usuario;
    public ?string $nombre;
    public string $fecha_creacion;


    public function __construct(
        int $id_usuario,
        ?string $nombre = null,
        ?string $fecha_creacion = null,

    ) {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->fecha_creacion = $fecha_creacion ?? date('Y-m-d');
    }
}
?>