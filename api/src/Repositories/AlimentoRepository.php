<?php

    namespace App\Repositories;

    use App\Repositories\Interfaces\AlimentoRepositoryInterface;
    use PDO;
    use PDOException;
    use Exception;

    class AlimentoRepository implements AlimentoRepositoryInterface
    {
        private PDO $pdo;

        public function __construct(PDO $pdo)
        {
            $this->pdo = $pdo;
        }

        public function createAlimento(
            string $nombre,
            float $calorias,
            float $proteinas,
            float $carbohidratos,
            float $grasas,
            string $familia,
            float $agua,
            float $fibra,
            string $categoria
        ): bool {
            try {
                $sql = "
                    INSERT INTO alimentos (nombre, calorias, proteinas, carbohidratos, grasas, familia, agua, fibra, categoria)
                    VALUES (:nombre, :calorias, :proteinas, :carbohidratos, :grasas, :familia, :agua, :fibra, :categoria)
                    ON DUPLICATE KEY UPDATE 
                        calorias = VALUES(calorias), 
                        proteinas = VALUES(proteinas),
                        carbohidratos = VALUES(carbohidratos),
                        grasas = VALUES(grasas),
                        familia = VALUES(familia),
                        agua = VALUES(agua),
                        fibra = VALUES(fibra),
                        categoria = VALUES(categoria)";
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':calorias', $calorias);
                $stmt->bindParam(':proteinas', $proteinas);
                $stmt->bindParam(':carbohidratos', $carbohidratos);
                $stmt->bindParam(':grasas', $grasas);
                $stmt->bindParam(':familia', $familia);
                $stmt->bindParam(':agua', $agua);
                $stmt->bindParam(':fibra', $fibra);
                $stmt->bindParam(':categoria', $categoria);

                return $stmt->execute();
            } catch (PDOException $e) {
                return false;
            }
        }

        public function getAlimentoPorId(int $id_alimento): ?array
        {
            $sql = "SELECT * FROM alimentos WHERE id_alimento = :id_alimento";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_alimento' => $id_alimento]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

        public function getAlimentoPorName(string $nombre): ?array
        {
            $sql = "SELECT id_alimento, nombre FROM alimentos WHERE nombre = :nombre";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':nombre' => $nombre]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

        public function getAlimentosFamilia(string $familia): ?array
        {
            $sql = "SELECT nombre FROM alimentos WHERE familia = :familia";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':familia' => $familia]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

        public function calcularValoresNutricionales(array $alimento, float $cantidad): array
        {
            return [
                'calorias_totales_alimento' => ($alimento['calorias'] * $cantidad) / 100,
                'proteinas_totales_alimento' => ($alimento['proteinas'] * $cantidad) / 100,
                'carbohidratos_totales_alimento' => ($alimento['carbohidratos'] * $cantidad) / 100,
                'grasas_totales_alimento' => ($alimento['grasas'] * $cantidad) / 100
            ];
        }

        public function getAlimentos(): array
        {
            try {
                $sql = "SELECT * FROM alimentos";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Error al obtener alimentos: " . $e->getMessage());
            }
        }
    }
?>