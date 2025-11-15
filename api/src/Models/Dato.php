<?php

    namespace App\Models;

    class Dato{
        public int $id_dato;
        public ?int $id_usuario = null;
        public ?int $edad = null;
        public ?float $altura = null;
        public ?float $peso = null;
        public ?string $genero = null;
        public string $grado_actividad;
        public string $objetivo;
        public ?float $peso_oseo_rocha = null;
        public ?float $porcentaje_oseo = null;
        public ?float $peso_residual = null;
        public ?float $porcentaje_residual = null;
        public ?float $peso_extracelular = null;
        public ?float $porcentaje_extracelular = null;
        public ?float $peso_intracelular = null;
        public ?float $porcentaje_intracelular = null;
        public ?float $imc = null;
        public ?float $cuello = null;
        public ?float $brazo = null;
        public ?float $cintura = null;
        public ?float $abdomen = null;
        public ?float $cadera = null;
        public ?float $muslo = null;
        public ?float $triceps = null;
        public ?float $subescapular = null;
        public ?float $abdomen_pliegue = null;
        public ?float $supra_iliaco = null;
        public ?float $muslo_pliegue = null;
        public ?float $porcentaje_graso_perimetros = null;
        public ?float $suma_pliegues = null;
        public ?float $porcentaje_graso_estimado_pliegues = null;
        public ?float $kg_grasa = null;
        public ?float $kg_masa_magra = null;
        public ?float $indice_masa_magra = null;
        public ?float $humero_biepicondileo = null;
        public ?float $femur_bicondileo = null;
        public ?float $muneca_estiloideo = null;
        public ?float $complex_osea = null;
        public ?float $muneca_circunferencia = null;
        public ?float $carbohidratos_datos = null;
        public ?float $calorias_datos = null;
        public ?float $grasas_datos = null;
        public ?float $proteinas_datos = null;
         public ?float $tdee= null;
        public ?string $nombre = null;
        public ?string $fecha = null;
    }
?>