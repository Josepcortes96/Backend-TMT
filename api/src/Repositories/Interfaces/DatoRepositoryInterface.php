<?php

    namespace App\Repositories\Interfaces;

    interface DatoRepositoryInterface{

        public function createDato(array $data):int;
        public function getDatoById(int $id_dato): array;
        public function getDatoByNombre(string $nombre, int $idUsuario): array;
        public function actualizarDato(int $id_dato, array $data): bool;
        public function deleteDato(int $id): bool;
        public function getAll():array;
        public function getPeso(int $id_usuario): float;
        public function getTodosControles(int $idUsuario): array;
         public function getUltimoControlPorId(int $idUsuario): ?array;
        
    }
?>