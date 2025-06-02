<?php

    namespace App\Modules\Dato;

    interface DatoRepositoryInterface{

        public function createDato(array $data):int;
        public function getDatoById(int $id_dato): array;
        public function getDatoByControl(string $control): array;
         public function actualizarDato(int $id_dato, array $data): bool;
        public function deleteDato(int $id): bool;
        public function getAll():array;
        public function getPeso(int $id_usuario): float;
    }
?>