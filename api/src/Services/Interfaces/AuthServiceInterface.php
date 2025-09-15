<?php

    namespace App\Services\Interfaces;

    interface AuthServiceInterface {
        public function login(string $correo, string $password): string;
        public function validarToken(string $token): bool;
    }

?>