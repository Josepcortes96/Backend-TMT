<?php

    namespace App\Repositories;

    use App\Repositories\Interfaces\ComidaRepositoryInterface;
    use PDO;
    use Exception;

    class ComidaRepository implements ComidaRepositoryInterface {
        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        public function createComida(string $tipo, string $hora, ?string $nota): int {
            $sql = "INSERT INTO comidas (tipo_comida, hora, calorias_totales_comida, proteinas_totales_comida, carbohidratos_totales_comida, grasas_totales_comida, notas)
                    VALUES (:tipo_comida, :hora, 0, 0, 0, 0, :notas)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':tipo_comida' => $tipo,
                ':hora' => $hora,
                ':notas' => $nota
            ]);
            return (int) $this->pdo->lastInsertId();
        }

        public function getComidaId(int $id): ?array {
            $sql = "SELECT id_comida FROM comidas WHERE id_comida = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public function asociarAlimentoComida(array $params): void {
            $sql = "INSERT INTO comida_alimento (id_comida, id_alimento, cantidad,
                    calorias_totales_alimento, proteinas_totales_alimento,
                    carbohidratos_totales_alimento, grasas_totales_alimento)
                    VALUES (:id_comida, :id_alimento, :cantidad, :calorias, :proteinas, :carbohidratos, :grasas)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_comida' => $params['id_comida'],
                ':id_alimento' => $params['id_alimento'],
                ':cantidad' => $params['cantidad'],
                ':calorias' => $params['calorias'],
                ':proteinas' => $params['proteinas'],
                ':carbohidratos' => $params['carbohidratos'],
                ':grasas' => $params['grasas']
            ]);
        }

        public function verificarAlimentoAsociado(int $comidaId, int $alimentoId): bool {
            $sql = "SELECT 1 FROM comida_alimento WHERE id_comida = :comida AND id_alimento = :alimento LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':comida' => $comidaId, ':alimento' => $alimentoId]);
            return $stmt->rowCount() > 0;
        }

        public function eliminarAlimentoDeComida(int $comidaId, int $alimentoId): bool {
            try {
                $sql = "DELETE FROM comida_alimento WHERE id_comida = :comida AND id_alimento = :alimento";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':comida', $comidaId);
                $stmt->bindParam(':alimento', $alimentoId);
                $stmt->execute();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public function actualizarCantidadAlimento(array $params): bool {
            try {
                $sql = "UPDATE comida_alimento SET cantidad = :cantidad,
                        calorias_totales_alimento = :calorias,
                        proteinas_totales_alimento = :proteinas,
                        carbohidratos_totales_alimento = :carbohidratos,
                        grasas_totales_alimento = :grasas
                        WHERE id_comida = :comida AND id_alimento = :alimento";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':cantidad' => $params['cantidad'],
                    ':calorias' => $params['calorias'],
                    ':proteinas' => $params['proteinas'],
                    ':carbohidratos' => $params['carbohidratos'],
                    ':grasas' => $params['grasas'],
                    ':comida' => $params['id_comida'],
                    ':alimento' => $params['id_alimento']
                ]);
                return $stmt->rowCount() > 0;
            } catch (Exception $e) {
                return false;
            }
        }
    }
?>