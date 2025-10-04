<?php
  
    namespace App\Services\Interfaces;
    use App\Models\User;

    interface UserServiceInterface{
        public function createUser(User $user): int;
        public function getAll(): array;
        public function getOne(int $id): array;
        public function updateUser(int $id, User $user): void;
        public function deleteUser(int $id): bool;
        public function desactivarUser(int $id): bool;
        public function getUserRol(int $id): string;
        public function getCentroId(int $id, string $rol): int;
         public function getUsersByCentro(int $centroId): array;
        public function getUserName(string $nombre): array;
        public function getUsersClientes(): array;
        public function getUsersPreparadores(): array;
        public function getUsersPropietarios(): array;  
        public function getCumpleañosUsers():array;
        public function getCumpleañosByCentro(int $centroId):array;
        public function getUltimosClientes():array;
        public function getUltimosClientesCentro(int $centroId):array;
        public function getActivos(): array;
        public function getActivosCentro(int $centroId): array;
        public function getUltimoNumero(int $centroId): array;
         

    }

?>