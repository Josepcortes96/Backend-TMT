<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DatoRepositoryInterface;
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

    public function getDatoByNombre(string $nombre, int $idUsuario): array
    {
        $sql = "SELECT * FROM datos d
        WHERE d.nombre = :nombre AND d.id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':id_usuario' => $idUsuario
        ]);

        $dato = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dato;

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

    /*FUNCION PARA RECIBIR LOS ULTIMSO 4 CONTROLES DEL USUARIO*/
    public function getUltimosControles(int $idUsuario): array
    {
          $sql = "SELECT * FROM datos 
            WHERE id_usuario = :id_usuario 
            ORDER BY id_dato DESC 
            LIMIT 3";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id_usuario' => $idUsuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /** FUNCION DE REPOSITORIO PARA MOSTRAR LOS NOMBRES DE LSO CONTROLES DEL USUARIO. */
     public function getTodosControles(int $idUsuario): array
    {
          $sql = "SELECT id_dato, nombre FROM datos 
            WHERE id_usuario = :id_usuario 
            ORDER BY id_dato DESC 
            ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id_usuario' => $idUsuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**FUNCION DE REPOSITORIO PARA MOSTRAR  */
    public function getUltimoControlPorId(int $idUsuario): ?array
{
    $sql = "SELECT * FROM datos 
            WHERE id_usuario = :id_usuario 
            ORDER BY id_dato DESC 
            LIMIT 1";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id_usuario' => $idUsuario]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}




}
?>