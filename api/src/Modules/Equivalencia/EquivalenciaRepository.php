<?php

namespace App\Repositories;

use App\Repositories\Interfaces\EquivalenciaRepositoryInterface;
use PDO;

/**
 * Repositorio para obtener información nutricional de alimentos y calcular equivalencias.
 */
class EquivalenciaRepository implements EquivalenciaRepositoryInterface{
    public function __construct(private PDO $pdo) {}

    /**
     * Obtiene un alimento por su ID.
     *
     * @param int $id ID del alimento.
     * @return array|null Datos del alimento o null si no existe.
     */
    public function obtenerAlimentoPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM alimentos WHERE id_alimento = :id");
        $stmt->execute([':id' => $id]);
        $alimento = $stmt->fetch(PDO::FETCH_ASSOC);

        return $alimento ?: null;
    }
}
?>