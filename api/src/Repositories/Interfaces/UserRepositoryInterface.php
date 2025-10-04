<?php

    namespace App\Repositories\Interfaces;
    use App\Models\User;

    interface UserRepositoryInterface {
        public function create(User $user): int;
        public function read(): array;
        public function getUser(int $id): array;
        public function update(int $id, User $user): void;
        public function delete(int $id): bool;
        public function desactivar(int $id): bool;
        public function getRol(int $id): string;
        public function relacionCentroUsuario(int $userId, int $centroId, string $rol): void;
        public function insertUsuarioRol(int $userId, string $rol): int;
        public function eliminarUsuarioTabla(int $id, string $rol): void;
        public function eliminarRelacionCentroRol(int $id, string $rol): void;
        public function eliminarRelacionDietaRol(int $id, string $rol): void;
        public function capturarCentroId(int $id, string $rol): ?int;
        public function capturarDietaId(int $id, string $rol): ?int;
        public function relacionDietaUsuario(int $userId, ?int $dietaId, string $rol): void;
        public function validarCentro(int $centroId): bool;
        public function getUsersByCentro(int $centroId): array;
        public function getUsersClientes(): array;
        public function getUsersPreparadores(): array;
        public function getUsersPropietarios():array;
        public function getCumpleañosUsers(): array;
        public function getCumpleañosByCentro(int $centroId): array;
        public function getUltimosClientes(): array;
        public function getUltimosClientesCentro(int $centroId): array;
        public function getActivos(): array;
        public function getActivosCentro(int $centroId): array;
        public function getUltimoNumero(int $centroId): array;
         
    }

?>