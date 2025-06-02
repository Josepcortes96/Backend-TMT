<?php

namespace App\Modules\Centro;

use PDO;
use Exception;
use App\Modules\Centro\CentroRepositoryInterface;

class CentroRepository implements CentroRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function createCentro(string $nombre, string $direccion, string $telefono, string $nombre_fiscal, string $NIF, string $ciudad, string $codigo_postal, string $pais, string $correo): bool {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO centros(nombre, direccion, telefono, fecha_creacion, nombre_fiscal, NIF, ciudad, codigo_postal, pais, correo)
                    VALUES (:nombre, :direccion, :telefono, NOW(), :nombre_fiscal, :NIF, :ciudad, :codigo_postal, :pais, :correo)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':nombre_fiscal', $nombre_fiscal);
            $stmt->bindParam(':NIF', $NIF);
            $stmt->bindParam(':ciudad', $ciudad);
            $stmt->bindParam(':codigo_postal', $codigo_postal);
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':correo', $correo);

            $success = $stmt->execute();

            $this->pdo->commit();
            return $success;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    public function updateCentro(int $id, string $nombre, string $direccion, string $telefono, string $nombre_fiscal, string $NIF, string $ciudad, string $codigo_postal, string $pais, string $correo): void {
        try {
            $this->pdo->beginTransaction();

            $sql = "UPDATE centros 
                    SET nombre = :nombre,
                        direccion = :direccion,
                        telefono = :telefono,
                        nombre_fiscal = :nombre_fiscal,
                        NIF = :NIF,
                        ciudad = :ciudad,
                        codigo_postal = :codigo_postal,
                        pais = :pais,
                        correo = :correo
                    WHERE id_centro = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':nombre_fiscal', $nombre_fiscal);
            $stmt->bindParam(':NIF', $NIF);
            $stmt->bindParam(':ciudad', $ciudad);
            $stmt->bindParam(':codigo_postal', $codigo_postal);
            $stmt->bindParam(':pais', $pais);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $this->pdo->commit();
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("ERROR AL EDITAR: " . $e->getMessage());
        }
    }

    public function getCentro(): array {
        $sql = "SELECT id_centro, nombre, direccion, telefono, nombre_fiscal, NIF, ciudad, codigo_postal, pais, correo FROM centros";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteCentro(int $id): bool {
        $sql = "DELETE FROM centros WHERE id_centro = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
