<?php

    namespace App\Repositories\Interfaces;

    interface AuthRepositoryInterface {
        public function verificarCredenciales(string $username, string $password): int;
    }
?>