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

        /**
         * Crea un nuevo alimento o actualiza sus valores si ya existe (según nombre).
         *
         * Usa `ON DUPLICATE KEY UPDATE` para actualizar los nutrientes de un alimento existente.
         *
         * @param string $nombre        Nombre del alimento.
         * @param float  $calorias      Calorías por cada 100g.
         * @param float  $proteinas     Proteínas por cada 100g.
         * @param float  $carbohidratos Carbohidratos por cada 100g.
         * @param float  $grasas        Grasas por cada 100g.
         * @param string $familia       Familia o grupo alimenticio.
         * @param float  $agua          Contenido de agua por cada 100g.
         * @param float  $fibra         Fibra por cada 100g.
         * @param string $categoria     Categoría del alimento.
         *
         * @return bool True si la operación fue exitosa, False si falló.
         */

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

        /**
         * Obtiene un alimento por su identificador único.
         *
         * @param int $id_alimento ID del alimento.
         *
         * @return array|null Datos completos del alimento o null si no existe.
         */

        public function getAlimentoPorId(int $id_alimento): ?array
        {
            $sql = "SELECT id_alimento, nombre,calorias,proteinas,carbohidratos,grasas,familia,categoria FROM alimentos WHERE id_alimento = :id_alimento";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_alimento' => $id_alimento]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

        /**
         * Busca un alimento por su nombre exacto.
         *
         * @param string $nombre Nombre del alimento.
         *
         * @return array|null Array con id y nombre del alimento o null si no existe.
         */

        public function getAlimentoPorName(string $nombre): ?array
        {
            $sql = "SELECT id_alimento, nombre FROM alimentos WHERE nombre = :nombre";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':nombre' => $nombre]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

        /**
         * Obtiene todos los alimentos pertenecientes a una familia específica.
         *
         * @param string $familia Nombre de la familia (ej. "Lácteos", "Carnes").
         *
         * @return array|null Lista de alimentos de la familia o null si no se encontraron resultados.
         */

        public function getAlimentosFamilia(string $familia): ?array
        {
            $sql = "SELECT nombre FROM alimentos WHERE familia = :familia";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':familia' => $familia]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: null;
        }

        /**
         * Calcula los valores nutricionales totales de un alimento en base a una cantidad dada.
         *
         * Los valores se calculan proporcionalmente tomando como referencia 100g.
         *
         * @param array $alimento Datos del alimento (debe contener calorías, proteínas, carbohidratos y grasas).
         * @param float $cantidad Cantidad en gramos.
         *
         * @return array {
         *     @type float calorias_totales_alimento
         *     @type float proteinas_totales_alimento
         *     @type float carbohidratos_totales_alimento
         *     @type float grasas_totales_alimento
         * }
         */

        public function calcularValoresNutricionales(array $alimento, float $cantidad): array
        {
            return [
                'calorias_totales_alimento' => ($alimento['calorias'] * $cantidad) / 100,
                'proteinas_totales_alimento' => ($alimento['proteinas'] * $cantidad) / 100,
                'carbohidratos_totales_alimento' => ($alimento['carbohidratos'] * $cantidad) / 100,
                'grasas_totales_alimento' => ($alimento['grasas'] * $cantidad) / 100
            ];
        }


        /**
         * Obtiene todos los alimentos de la base de datos.
         *
         * @return array[] Lista de alimentos en formato asociativo.
         *
         * @throws Exception Si ocurre un error al consultar la base de datos.
         */

        public function getAlimentos(): array
        {
            try {
                $sql = "SELECT id_alimento, nombre,calorias,proteinas,carbohidratos,grasas,familia,categoria FROM alimentos";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new Exception("Error al obtener alimentos: " . $e->getMessage());
            }
        }
    }
?>