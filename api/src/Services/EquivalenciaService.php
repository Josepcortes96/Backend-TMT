<?php

namespace App\Services;

use App\Repositories\Interfaces\EquivalenciaRepositoryInterface;
use App\Services\Interfaces\EquivalenciaServiceInterface;

/**
 * Servicio para calcular equivalencias nutricionales entre alimentos.
 */
class EquivalenciaService implements EquivalenciaServiceInterface
{
    public function __construct(private EquivalenciaRepositoryInterface $repo) {}

    /**
     * Calcula la equivalencia entre dos alimentos según una categoría nutricional.
     *
     * @param int $idAlimento ID del alimento base.
     * @param int $idEquivalente ID del alimento equivalente.
     * @param string $categoria Categoría nutricional a comparar.
     * @param float $cantidadBase Cantidad del alimento base.
     * @return array Resultado de la equivalencia o error detallado.
     */
    public function calcularEquivalencia(int $idAlimento, int $idEquivalente, string $categoria, float $cantidadBase): array
    {
        $alimentoBase = $this->repo->obtenerAlimentoPorId($idAlimento);
        if (!$alimentoBase) {
            return ['error' => 'Alimento base no encontrado'];
        }

        $alimentoEquivalente = $this->repo->obtenerAlimentoPorId($idEquivalente);
        if (!$alimentoEquivalente) {
            return ['error' => 'Alimento equivalente no encontrado'];
        }

        switch (strtolower($categoria)) {
            case 'proteina':
                $valorBase = $alimentoBase['proteinas'] ?? 0;
                $valorEq = $alimentoEquivalente['proteinas'] ?? 0;
                break;
            case 'carbohidrato':
                $valorBase = $alimentoBase['carbohidratos'] ?? 0;
                $valorEq = $alimentoEquivalente['carbohidratos'] ?? 0;
                break;
            case 'grasa':
                $valorBase = $alimentoBase['grasas'] ?? 0;
                $valorEq = $alimentoEquivalente['grasas'] ?? 0;
                break;
            case 'fruta':
            case 'verdura':
                $valorBase = $alimentoBase['calorias'] ?? 0;
                $valorEq = $alimentoEquivalente['calorias'] ?? 0;
                break;
            default:
                return ['error' => 'Categoría no válida'];
        }

        if ($valorEq == 0) {
            return ['error' => 'El alimento equivalente tiene valor 0 en esa categoría'];
        }

        $cantidadEquivalente = ($cantidadBase * $valorBase) / $valorEq;

        return [
            'principal' => [
                'id' => $idAlimento,
                'cantidad' => round($cantidadBase, 2)
            ],
            'equivalencia' => [
                'id_alimento_equivalente' => $idEquivalente,
                'cantidad_equivalente' => round($cantidadEquivalente, 2)
            ]
        ];
    }   
}