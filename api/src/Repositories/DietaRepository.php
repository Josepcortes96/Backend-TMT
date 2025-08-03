<?php
namespace App\Repositories;
use App\Repositories\Interfaces\DietaRepositoryInterface;
use PDO;
use Exception;

class DietaRepository implements DietaRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

            public function createDieta(?string $nombre, ?string $descripcion, int $id_usuario, int $id_dato,float $calorias_dieta, float $proteinas_dieta, float $grasas_dieta, float $carbohidratos_dieta, ?string $fecha_creacion = null): int{
                
                    $sql = "INSERT INTO dietas (
                                nombre, descripcion, id_usuario, id_dato, calorias_dieta,proteinas_dieta, grasas_dieta, carbohidratos_dieta, fecha_creacion
                            ) VALUES (
                                :nombre, :descripcion, :id_usuario, :id_dato, :calorias_dieta, :proteinas_dieta, :grasas_dieta, :carbohidratos_dieta, :fecha_creacion
                            )";

                    $stmt = $this->pdo->prepare($sql);

                    try {
                        $stmt->execute([
                            ':nombre' => $nombre,
                            ':descripcion' => $descripcion,
                            ':id_usuario' => $id_usuario,
                            ':id_dato' => $id_dato,
                            ':calorias_dieta' => $calorias_dieta,
                            ':proteinas_dieta' => $proteinas_dieta,
                            ':grasas_dieta' => $grasas_dieta,
                            ':carbohidratos_dieta' => $carbohidratos_dieta,
                            ':fecha_creacion' => $fecha_creacion ?? date('Y-m-d')
                        ]);
                    } catch (Exception $e) {
                        throw new Exception("Error al crear la dieta: " . $e->getMessage());
                    }

                    $id_dieta = $this->pdo->lastInsertId();

                    if (!$id_dieta) {
                        throw new Exception("No se pudo obtener el ID de la dieta recién creada.");
                    }

                    return $id_dieta;
            }

    

    public function getDietas():array {
        $sql = "SELECT * FROM dietas";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDietaById($id_dieta):bool {
        $sql = "SELECT COUNT(*) FROM dietas WHERE id_dieta = :id_dieta";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_dieta' => $id_dieta]);
        return $stmt->fetchColumn() > 0;
    }

    public function getDieta($id_dieta):array {
        $sql = "SELECT nombre, descripcion FROM dietas WHERE id_dieta = :id_dieta";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_dieta', $id_dieta, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteDieta($id_dieta):bool {
        $sql = "DELETE FROM dietas WHERE id_dieta = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id_dieta);
        return $stmt->execute();
    }

    public function asociarComidaDieta($id_dieta, $id_comida):void {
        if (!is_int($id_dieta) || !is_int($id_comida)) {
            throw new Exception("Los IDs deben ser números enteros.");
        }

        $check = $this->pdo->prepare("SELECT COUNT(*) FROM dieta_comida WHERE id_dieta = :id_dieta AND id_comida = :id_comida");
        $check->execute([
            ':id_dieta' => $id_dieta,
            ':id_comida' => $id_comida
        ]);

        if ((int)$check->fetchColumn() > 0) {
            return;
        }

        $sql = "INSERT INTO dieta_comida (id_dieta, id_comida) VALUES (:id_dieta, :id_comida)";
        $stmt = $this->pdo->prepare($sql);

        try {
            $stmt->execute([
                ':id_dieta' => $id_dieta,
                ':id_comida' => $id_comida
            ]);
        } catch (Exception $e) {
            throw new Exception("Error al asociar comida a dieta: " . $e->getMessage());
        }
    }

    public function actualizarDieta($id_dieta, $proteinas_dieta, $grasas_dieta, $carbohidratos_dieta):array {
        $stmt = $this->pdo->prepare("
            UPDATE dietas
            SET proteinas_dieta = :proteinas_dieta,
                grasas_dieta = :grasas_dieta,
                carbohidratos_dieta = :carbohidratos_dieta
            WHERE id_dieta = :id_dieta
        ");
        $stmt->execute([
            'id_dieta' => $id_dieta,
            'proteinas_dieta' => $proteinas_dieta,
            'grasas_dieta' => $grasas_dieta,
            'carbohidratos_dieta' => $carbohidratos_dieta
        ]);

        if ($stmt->rowCount() > 0) {
            return ["message" => "Dieta actualizada correctamente"];
        } else {
            return ["error" => "No se pudo actualizar la dieta"];
        }
    }
}
?>