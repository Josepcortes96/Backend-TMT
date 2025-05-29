<?php

    namespace App\Services\Interfaces;

    interface AuthServiceInterface {
        public function login(string $username, string $password): string;
        public function validarToken(string $token): bool;
    }

?>