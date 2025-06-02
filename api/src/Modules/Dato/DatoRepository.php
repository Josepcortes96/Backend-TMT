<?php

namespace App\Modules\Dato;

use App\Modules\Dato\DatoRepositoryInterface;
use PDO;
use Exception;

class DatoRepository implements DatoRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createDato(array $data): int
    {
        $campos = array_keys($data);
        $placeholders = array_map(fn($campo) => ':' . $campo, $campos);

        $sql = "INSERT INTO datos (" . implode(',', $campos) . ")
                VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute($data)) {
            throw new Exception("Error al insertar el dato.");
        }

        return (int) $this->pdo->lastInsertId();
    }

    public function getDatoById(int $id_dato): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM datos WHERE id_dato = :id");
        $stmt->execute([':id' => $id_dato]);
        $dato = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dato;
    }

    public function getDatoByControl(string $control): array
    {
        $sql = "SELECT d.* FROM datos d
                
                WHERE d.control = :control";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':control' => $control]);

        $dato = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dato ;
    }

    public function actualizarDato(int $id_dato, array $data): bool
    {
        $fields = array_map(fn($campo) => "$campo = :$campo", array_keys($data));
        $sql = "UPDATE datos SET " . implode(', ', $fields) . " WHERE id_dato = :id_dato";

        $stmt = $this->pdo->prepare($sql);
        $data['id_dato'] = $id_dato;

        return $stmt->execute($data);
    }

    public function deleteDato(int $id_dato): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM datos WHERE id_dato = :id");
        return $stmt->execute([':id' => $id_dato]);
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM datos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeso(int $id_usuario): float{
        $query = "
            SELECT d.peso
            FROM datos d
            WHERE d.id_usuario = :id_usuario
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || !isset($result['peso'])) {
            throw new Exception("No se encontró el peso para el usuario con ID $id_usuario.");
        }

        return (float) $result['peso'];
    }

}
?>