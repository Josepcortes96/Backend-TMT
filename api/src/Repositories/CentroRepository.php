<?php

namespace App\Repositories;

use PDO;
use Exception;
use App\Repositories\Interfaces\CentroRepositoryInterface;

class CentroRepository implements CentroRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Crea un nuevo centro en la base de datos.
     *
     * @param string $nombre        Nombre del centro.
     * @param string $direccion     Dirección del centro.
     * @param string $telefono      Teléfono de contacto.
     * @param string $nombre_fiscal Razón social o nombre fiscal.
     * @param string $NIF           Número de identificación fiscal.
     * @param string $ciudad        Ciudad.
     * @param string $codigo_postal Código postal.
     * @param string $pais          País.
     * @param string $correo        Correo electrónico.
     *
     * @return bool True si la creación fue exitosa, False en caso contrario.
     */

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


    /**
     * Actualiza los datos de un centro existente.
     *
     * @param int    $id            ID del centro.
     * @param string $nombre        Nombre del centro.
     * @param string $direccion     Dirección del centro.
     * @param string $telefono      Teléfono.
     * @param string $nombre_fiscal Nombre fiscal.
     * @param string $NIF           Número fiscal.
     * @param string $ciudad        Ciudad.
     * @param string $codigo_postal Código postal.
     * @param string $pais          País.
     * @param string $correo        Correo electrónico.
     *
     * @throws Exception Si ocurre un error durante la actualización.
     *
     * @return void
     */

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


     /**
     * Obtiene todos los centros registrados.
     *
     * @return array[] Lista de centros con estructura:
     * [
     *   @type int    $id_centro
     *   @type string $nombre
     *   @type string $direccion
     *   @type string $telefono
     *   @type string $nombre_fiscal
     *   @type string $NIF
     *   @type string $ciudad
     *   @type string $codigo_postal
     *   @type string $pais
     *   @type string $correo
     * ]
     */

    public function getCentro(): array {
        $sql = "SELECT id_centro, nombre, direccion, telefono, nombre_fiscal, NIF, ciudad, codigo_postal, pais, correo FROM centros";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    /**
     * Elimina un centro de la base de datos.
     *
     * @param int $id ID del centro.
     *
     * @return bool True si la eliminación fue exitosa, False si falló.
     */

    public function deleteCentro(int $id): bool {
        $sql = "DELETE FROM centros WHERE id_centro = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
