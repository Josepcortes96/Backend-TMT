<?php

    namespace App\Modules\Auth;

    interface AuthRepositoryInterface {
        public function verificarCredenciales(string $username, string $password): int;
    }
?>