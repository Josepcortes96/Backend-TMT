<?php

    namespace App\Models;

    class User{
        public string $username;
        public string $nombre;
        public string $apellidos;
        public string $password;
        public string $rol;
        public string $correo;
        public string $estado;
        public string $telefono;
        public string $direccion;
        public string $ciudad;
        public string $fechaNacimiento;
        public int $centroId;
        public ?int $edad = null;
        public ?string $altura = null;
        public ?string $peso = null;

        public function __construct(array $data) {
            $this->username = $data['username'];
            $this->nombre = $data['nombre'];
            $this->apellidos = $data['apellidos'];
            $this->password = $data['password'];
            $this->rol = $data['rol'];
            $this->correo = $data['correo'];
            $this->estado = $data['estado'];
            $this->telefono = $data['telefono'];
            $this->direccion = $data['direccion'];
            $this->ciudad = $data['ciudad'];
            $this->fechaNacimiento = $data['fecha_de_nacimiento'];
            $this->centroId = $data['id_centro'];
            $this->edad = $data['edad'] ?? null;
            $this->altura = $data['altura'] ?? null;
            $this->peso = $data['peso'] ?? null;
        }
    }
?>