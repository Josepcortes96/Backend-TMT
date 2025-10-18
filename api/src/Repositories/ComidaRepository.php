<?php

namespace App\Services;

use App\Repositories\Interfaces\AlimentoRepositoryInterface;
use App\Repositories\Interfaces\ComidaRepositoryInterface;
use App\Services\Interfaces\ComidaServiceInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @throws Exception Si ocurre un error durante la creación
     */
    public function crearComidasConAlimentos(array $datos): array {
        DB::beginTransaction();
        
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

                    // Asociar alimento con la comida
                    $this->repo->asociarAlimento(
                        $idComida,
                        $al['id_alimento'],
                        $al['cantidad'],
                        $valores,
                        $al['categoria'] ?? null
                    );

                    // Insertar equivalencias si existen
                    if (isset($al['equivalentes'])) {
                        $this->insertarEquivalencias($idComida, $al['id_alimento'], $al['equivalentes']);
                    }
                }

                // Actualizar totales de la comida
                $this->repo->actualizarTotalesComida($idComida);
                $respuestas[] = ['id_comida' => $idComida];
            }

            DB::commit();
            
            Log::info('Comidas creadas exitosamente', ['count' => count($respuestas)]);
            
            return $respuestas;
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error al crear comidas con alimentos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new Exception('Error al crear las comidas: ' . $e->getMessage());
        }
    }

    /**
     * Inserta todas las equivalencias de un alimento
     * @param int $idComida ID de la comida
     * @param int $idAlimento ID del alimento principal
     * @param array $equivalentes Array de equivalencias
     * @throws Exception Si ocurre un error
     */
    private function insertarEquivalencias(int $idComida, int $idAlimento, array $equivalentes): void {
        $metodosEquivalencia = [
            'id_alimento_equivalente' => 'insertarEquivalencia',
            'id_alimento_equivalente1' => 'insertarEquivalencia1',
            'id_alimento_equivalente3' => 'insertarEquivalencia3',
            'id_alimento_equivalente4' => 'insertarEquivalencia4',
            'id_alimento_equivalente5' => 'insertarEquivalencia5',
            'id_alimento_equivalente6' => 'insertarEquivalencia6',
            'id_alimento_equivalente7' => 'insertarEquivalencia7',
            'id_alimento_equivalente8' => 'insertarEquivalencia8',
            'id_alimento_equivalente9' => 'insertarEquivalencia9',
            'id_alimento_equivalente10' => 'insertarEquivalencia10',
        ];

        foreach ($equivalentes as $eq) {
            foreach ($metodosEquivalencia as $campo => $metodo) {
                if (isset($eq[$campo])) {
                    $campoCantidad = str_replace('id_alimento_', 'cantidad_', $campo);
                    
                    $this->repo->$metodo(
                        $idComida,
                        $idAlimento,
                        $eq[$campo],
                        $eq[$campoCantidad] ?? null
                    );
                }
            }
        }
    }

    /**
     * Funcion para agregar a las comidas alimentos adicionales
     * @param array $datos. Estructura de la comida donde se van a añadir los alimentos.
     * @return array Retorna la comida actualizada
     * @throws Exception Si ocurre un error durante la adición
     */
    public function agregarAlimentosAComida(array $datos): array {
        DB::beginTransaction();
        
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
            
            DB::commit();
            
            Log::info('Alimentos agregados a comida', ['id_comida' => $idComida]);
            
            return ['id_comida' => $idComida];
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error al agregar alimentos a comida', [
                'id_comida' => $datos['id_comida'] ?? null,
                'error' => $e->getMessage()
            ]);
            
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
        DB::beginTransaction();
        
        try {
            if (empty($comidaIds)) {
                throw new Exception('No se proporcionaron IDs de comidas para eliminar');
            }
            
            $resultado = $this->repo->eliminarComidas($comidaIds);
            
            if (!$resultado) {
                throw new Exception('Error al eliminar las comidas en el repositorio');
            }
            
            DB::commit();
            
            Log::info('Comidas eliminadas exitosamente', ['ids' => $comidaIds]);
            
            return true;
            
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error al eliminar comidas', [
                'ids' => $comidaIds,
                'error' => $e->getMessage()
            ]);
            
            throw new Exception('Error al eliminar las comidas: ' . $e->getMessage());
        }
    }
}
?>