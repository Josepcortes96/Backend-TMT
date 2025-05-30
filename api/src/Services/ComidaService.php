<?php

    namespace App\Services;

    use App\Repositories\Interfaces\ComidaRepositoryInterface;
    use App\Services\Interfaces\ComidaServiceInterface;
    use Exception;

    class ComidaService implements ComidaServiceInterface {
        public function __construct(
            private ComidaRepositoryInterface $repo,
            private $alimentoModel
        ) {}

        public function crearComidasConAlimentos(array $datos): array {
            $respuestas = [];
            foreach ($datos as $comidaData) {
                $idComida = $this->repo->createComida($comidaData);
                foreach ($comidaData['alimentos'] as $al) {
                    $alimento = $this->alimentoModel->getAlimentoPorId($al['id_alimento']);
                    $valores = $this->alimentoModel->calcularValoresNutricionales($alimento, $al['cantidad']);
                    $this->repo->asociarAlimento($idComida, $al['id_alimento'], $al['cantidad'], $valores);

                    if (isset($al['equivalentes'])) {
                        foreach ($al['equivalentes'] as $eq) {
                            if (isset($eq['id_alimento_equivalente'])) {
                                $this->repo->insertarEquivalencia($idComida, $al['id_alimento'], $eq['id_alimento_equivalente'], $eq['cantidad_equivalente']);
                            }
                            if (isset($eq['id_alimento_equivalente1'])) {
                                $this->repo->insertarEquivalencia1($idComida, $al['id_alimento'], $eq['id_alimento_equivalente1'], $eq['cantidad_equivalente1']);
                            }
                            if (isset($eq['id_alimento_equivalente3'])) {
                                $this->repo->insertarEquivalencia3($idComida, $al['id_alimento'], $eq['id_alimento_equivalente3'], $eq['cantidad_equivalente3']);
                            }
                        }
                    }
                }
                $this->repo->actualizarTotalesComida($idComida);
                $respuestas[] = ['id_comida' => $idComida];
            }
            return $respuestas;
        }

        public function agregarAlimentosAComida(array $datos): array {
            $idComida = $datos['id_comida'];
            foreach ($datos['alimentos'] as $al) {
                $alimento = $this->alimentoModel->getAlimentoPorId($al['id_alimento']);
                $valores = $this->alimentoModel->calcularValoresNutricionales($alimento, $al['cantidad']);
                $this->repo->agregarAlimento($idComida, $al['id_alimento'], $al['cantidad'], $valores);
            }
            $this->repo->actualizarTotalesComida($idComida);
            return ['id_comida' => $idComida];
        }
    }
?>