<?php

namespace App\Services;

use App\Repositories\Interfaces\AlimentoRepositoryInterface;
use App\Repositories\Interfaces\ComidaRepositoryInterface;
use App\Services\Interfaces\ComidaServiceInterface;
use Exception;
use PDO;

/**
 * Servicio para manejar la logica de negocio de las comidas.
 */
class ComidaService implements ComidaServiceInterface {
    private PDO $pdo;
    
    public function __construct(
        private ComidaRepositoryInterface $repo,
        private AlimentoRepositoryInterface $alimentoRepository,
        PDO $pdo
    ) {
        $this->pdo = $pdo;
    }
    
    /**
     * Funcion para crear distintas comidas con los alimentos que estan asociados asi como los valores nutricionales de estos.
     * @param array $datos, Son los datos del tipo de comida con sus valores y los alimentos.
     * @return array Ids de las comidas que han sido creadas 
     * @throws Exception Si ocurre un error durante la creación
     */
    public function crearComidasConAlimentos(array $datos): array {
        $this->pdo->beginTransaction();
        
        try {
            $respuestas = [];

            foreach ($datos as $comidaData) {
                $idComida = $this->repo->createComida($comidaData);

                foreach ($comidaData['alimentos'] as $al) {
                    $alimento = $this->alimentoRepository->getAlimentoPorId($al['id_alimento']);
                    
                    if (!$alimento) {
                        throw new Exception("Alimento con ID {$al['id_alimento']} no encontrado");
                    }
                    
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

                            if (isset($eq['id_alimento_equivalente4'])) {
                                $this->repo->insertarEquivalencia4(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente4'],
                                    $eq['cantidad_equivalente4']
                                );
                            }

                            if (isset($eq['id_alimento_equivalente5'])) {
                                $this->repo->insertarEquivalencia5(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente5'],
                                    $eq['cantidad_equivalente5']
                                );
                            }

                            if (isset($eq['id_alimento_equivalente6'])) {
                                $this->repo->insertarEquivalencia6(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente6'],
                                    $eq['cantidad_equivalente6']
                                );
                            }

                            if (isset($eq['id_alimento_equivalente7'])) {
                                $this->repo->insertarEquivalencia7(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente7'],
                                    $eq['cantidad_equivalente7']
                                );
                            }

                            if (isset($eq['id_alimento_equivalente8'])) {
                                $this->repo->insertarEquivalencia8(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente8'],
                                    $eq['cantidad_equivalente8']
                                );
                            }

                            if (isset($eq['id_alimento_equivalente9'])) {
                                $this->repo->insertarEquivalencia9(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente9'],
                                    $eq['cantidad_equivalente9']
                                );
                            }

                            if (isset($eq['id_alimento_equivalente10'])) {
                                $this->repo->insertarEquivalencia10(
                                    $idComida,
                                    $al['id_alimento'],
                                    $eq['id_alimento_equivalente10'],
                                    $eq['cantidad_equivalente10']
                                );
                            }
                        }
                    }
                }

                $this->repo->actualizarTotalesComida($idComida);
                $respuestas[] = ['id_comida' => $idComida];
            }

            $this->pdo->commit();
            
            error_log('✅ Comidas creadas exitosamente: ' . count($respuestas));
            
            return $respuestas;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            
            error_log('❌ Error al crear comidas: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            throw new Exception('Error al crear las comidas: ' . $e->getMessage());
        }
    }

    /**
     * Funcion para agregar a las comidas alimentos adicionales
     * @param array $datos. Estructura de la comida donde se van a añadir los alimentos.
     * @return array Retorna la comida actualizada
     * @throws Exception Si ocurre un error durante la adición
     */
    public function agregarAlimentosAComida(array $datos): array {
        $this->pdo->beginTransaction();
        
        try {
            $idComida = $datos['id_comida'];
            
            if (!$idComida) {
                throw new Exception('ID de comida no proporcionado');
            }
            
            foreach ($datos['alimentos'] as $al) {
                $alimento = $this->alimentoRepository->getAlimentoPorId($al['id_alimento']);
                
                if (!$alimento) {
                    throw new Exception("Alimento con ID {$al['id_alimento']} no encontrado");
                }
                
                $valores = $this->alimentoRepository->calcularValoresNutricionales($alimento, $al['cantidad']);
                $this->repo->agregarAlimento($idComida, $al['id_alimento'], $al['cantidad'], $valores);
            }
            
            $this->repo->actualizarTotalesComida($idComida);
            
            $this->pdo->commit();
            
            error_log('✅ Alimentos agregados a comida: ' . $idComida);
            
            return ['id_comida' => $idComida];
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            
            error_log('❌ Error al agregar alimentos: ' . $e->getMessage());
            
            throw new Exception('Error al agregar alimentos a la comida: ' . $e->getMessage());
        }
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
     * @throws Exception Si ocurre un error durante la eliminación
     */
    public function eliminarComidas(array $comidaIds): bool {
        $this->pdo->beginTransaction();
        
        try {
            if (empty($comidaIds)) {
                throw new Exception('No se proporcionaron IDs de comidas para eliminar');
            }
            
            $resultado = $this->repo->eliminarComidas($comidaIds);
            
            if (!$resultado) {
                throw new Exception('Error al eliminar las comidas en el repositorio');
            }
            
            $this->pdo->commit();
            
            error_log('✅ Comidas eliminadas: ' . implode(', ', $comidaIds));
            
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            
            error_log('❌ Error al eliminar comidas: ' . $e->getMessage());
            
            throw new Exception('Error al eliminar las comidas: ' . $e->getMessage());
        }
    }
}
?>