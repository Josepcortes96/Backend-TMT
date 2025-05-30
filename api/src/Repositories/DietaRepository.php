<?php

    namespace App\Repositories;

    use App\Repositories\Interfaces\DietaRepositoryInterface;
    use PDO;

    class DietaRepository implements DietaRepositoryInterface {
        public function __construct(private PDO $pdo) {}

        public function create(string $nombre, string $descripcion , int $id_dato): int {
            $sql = "INSERT INTO dietas (nombre, descripcion, id_dato) VALUES (:nombre, :descripcion, :id_dato)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion]);
            return (int)$this->pdo->lastInsertId();
        }

        public function getAll(): array {
            $stmt = $this->pdo->query("SELECT * FROM dietas");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getById(int $id): ?array {
            $stmt = $this->pdo->prepare("SELECT nombre, descripcion FROM dietas WHERE id_dieta = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        public function exists(int $id): bool {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM dietas WHERE id_dieta = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetchColumn() > 0;
        }

        public function delete(int $id): bool {
            $stmt = $this->pdo->prepare("DELETE FROM dietas WHERE id_dieta = :id");
            return $stmt->execute([':id' => $id]);
        }

        public function asociarComida(int $id_dieta, int $id_comida): void {
            $stmt = $this->pdo->prepare("INSERT INTO dieta_comida (id_dieta, id_comida) VALUES (:id_dieta, :id_comida)");
            $stmt->execute([':id_dieta' => $id_dieta, ':id_comida' => $id_comida]);
        }

        public function updateMacros(int $id_dieta, float $proteinas, float $grasas, float $carbohidratos): bool {
            $sql = "UPDATE dietas SET proteinas_dieta = :proteinas, grasas_dieta = :grasas, carbohidratos_dieta = :carbohidratos WHERE id_dieta = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id_dieta,
                ':proteinas' => $proteinas,
                ':grasas' => $grasas,
                ':carbohidratos' => $carbohidratos
            ]);
            return $stmt->rowCount() > 0;
        }
    }
?>