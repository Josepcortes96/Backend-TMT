<?php

    namespace App\Models;

    class Centro{
        public string $nombre;
        public string $direccion;
        public string $telefono;
        public string $nombre_fiscal;
        public string $NIF;
        public string $ciudad;
        public string $codigo_postal;
        public string $pais;
        public string $correo;
    

        public function __construct(array $data) {
            $this->nombre = $data['nombre'];
            $this->direccion = $data['direccion'];
            $this->telefono = $data['telefono'];
            $this->nombre_fiscal = $data['nombre_fiscal'];
            $this->NIF = $data['NIF'];
            $this->ciudad = $data['ciudad'];
            $this->codigo_postal = $data['codigo_postal'];
            $this->pais = $data['pais'];
            $this->correo = $data['correo'];
            
        }
    }
?>