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
       

    
        public function __construct(array $data) {
            $this->username = $data['username'] ?? null;
            $this->nombre = $data['nombre'] ?? null;
            $this->apellidos = $data['apellidos'] ?? null;
            $this->password = $data['password'] ?? null;
            $this->rol = $data['rol'] ?? null;
            $this->correo = $data['correo'] ?? null;
            $this->estado = $data['estado'] ?? null;
            $this->telefono = $data['telefono'] ?? null;
            $this->direccion = $data['direccion'] ?? null;
            $this->ciudad = $data['ciudad'] ?? null;
            $this->fechaNacimiento = $data['fecha_de_nacimiento'] ?? null;
            $this->centroId = $data['id_centro'] ?? null;
        }

    }
?>