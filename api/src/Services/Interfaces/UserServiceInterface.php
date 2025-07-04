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
         

    }

?>