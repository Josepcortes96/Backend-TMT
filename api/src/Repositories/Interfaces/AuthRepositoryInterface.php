<?php

    namespace App\Repositories\Interfaces;

    interface AuthRepositoryInterface {
        public function verificarCredenciales(string $username, string $password): int;

        public function registrarLogin(int $id_usuario, string $ip, string $userAgent, string $jwtHash): void;
    }
?>