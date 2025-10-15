<?php

namespace App\Models;
use DateTime;
use Exception;

class User {

    public string $nombre;
    public string $apellidos;
    public ?string $password;
    public string $rol;
    public string $correo;
    public string $estado;
    public string $telefono;
    public string $direccion;
    public string $ciudad;
    public string $fechaNacimiento; 
    public string $numero_usuario;
    public int $centroId;

    public function __construct(array $data) {

        $this->nombre = $data['nombre'] ?? '';
        $this->apellidos = $data['apellidos'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->rol = $data['rol'] ?? '';
        $this->correo = $data['correo'] ?? '';
        $this->estado = $data['estado'] ?? '';
        $this->telefono = $data['telefono'] ?? '';
        $this->direccion = $data['direccion'] ?? '';
        $this->ciudad = $data['ciudad'] ?? '';
        $this ->numero_usuario = $data['numero_usuario'] ?? '';

   
        if (!empty($data['fecha_de_nacimiento'])) {
            $fecha = DateTime::createFromFormat('Y-m-d', $data['fecha_de_nacimiento']);
            if ($fecha) {
                $this->fechaNacimiento = $fecha->format('Y-m-d');
            } else {
                throw new Exception("Formato de fecha no válido");
            }
        } else {
            $this->fechaNacimiento = '';
        }

        $this->centroId = $data['id_centro'] ?? 0;
    }
}
?>
