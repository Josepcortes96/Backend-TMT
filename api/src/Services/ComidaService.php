<?php

    namespace App\Services;

use App\Repositories\Interfaces\AlimentoRepositoryInterface;
use App\Repositories\Interfaces\ComidaRepositoryInterface;
    use App\Services\Interfaces\ComidaServiceInterface;
   
    /**
     * Servicio para manejar la logica de negocio de las comidas.
     */
    class ComidaService implements ComidaServiceInterface {
        public function __construct(
            private ComidaRepositoryInterface $repo,
            private AlimentoRepositoryInterface $alimentoRepository
        ) {}
        
        /**
         * Funcion para crear distintas comidas con los alimentos que estan asociados asi como los valores nutricionales de estos.
         * @param array $datos, Son los datos del tipo de comida con sus valores y los alimentos.
         * @return array Ids de las comidas que han sido creadas 
         */

        public function crearComidasConAlimentos(array $datos): array {
            $respuestas = [];

            foreach ($datos as $comidaData) {
                $idComida = $this->repo->createComida($comidaData);

                foreach ($comidaData['alimentos'] as $al) {
                    $alimento = $this->alimentoRepository->getAlimentoPorId($al['id_alimento']);
                    $valores = $this->alimentoRepository->calcularValoresNutricionales($alimento, $al['cantidad']);

                    // ahora incluimos la categoria si viene del frontend
                    $this->repo->asociarAlimento(
                        $idComida,
                        $al['id_alimento'],
                        $al['cantidad'],
                        $valores,
                        $al['categoria'] ?? null
                    );

                    if (isset($al['equivalentes'])) {
                        foreach ($al['equivalentes'] as $eq) {
                            if (isset($eq['id_alimento_equivalente'])) {
                                $this->repo->insertarEquivalencia(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente'],
                                    $eq['cantidad_equivalente']
                                );
                            }
                            if (isset($eq['id_alimento_equivalente1'])) {
                                $this->repo->insertarEquivalencia1(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente1'],
                                    $eq['cantidad_equivalente1']
                                );
                            }
                            if (isset($eq['id_alimento_equivalente3'])) {
                                $this->repo->insertarEquivalencia3(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente3'],
                                    $eq['cantidad_equivalente3']
                                );
                            }
                        }
                    }
                }

                $this->repo->actualizarTotalesComida($idComida);
                $respuestas[] = ['id_comida' => $idComida];
            }

            return $respuestas;
        }


        /**
         * Funcion para agregar a las comidas alimentos adicionales
         * @param array $datos. Estructura de la comida donde se van a añadir los alimentos.
         * @return array Retorna la comida actualizada
         */

        public function agregarAlimentosAComida(array $datos): array {
            $idComida = $datos['id_comida'];
            foreach ($datos['alimentos'] as $al) {
                $alimento = $this->alimentoRepository->getAlimentoPorId($al['id_alimento']);
                $valores = $this->alimentoRepository->calcularValoresNutricionales($alimento, $al['cantidad']);
                $this->repo->agregarAlimento($idComida, $al['id_alimento'], $al['cantidad'], $valores);
            }
            $this->repo->actualizarTotalesComida($idComida);
            return ['id_comida' => $idComida];
        }

        /**
         * Elimina varias comidas de la base de datos.
         *
         * Este método actúa como intermediario entre la capa de aplicación/servicio
         * y el repositorio (`$this->repo`). 
         * 
         * @param int[] $comidaIds Lista de identificadores de comidas a eliminar.
         *
         * @return bool True si la eliminación fue exitosa, False en caso contrario.
         */

        public function eliminarComidas(array $comidaIds): bool {
            return $this->repo->eliminarComidas($comidaIds);
        }

    }
?>