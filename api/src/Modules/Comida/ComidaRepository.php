<?php

    namespace App\Modules\Comida;

    use App\Modules\Comida\ComidaRepositoryInterface;
    use PDO;
    /**
     * Repositorio que se encarga de manejar las operaciones bases a la base de datos
     */
    class ComidaRepository implements ComidaRepositoryInterface {
        public function __construct(private PDO $pdo) {}
        
        /**
         * Funcion para crear una nueva comida.
         * @param array $data. Datos que tiene que tener la comida.
         * @return int. Devuelve el id de la comida creada
         */

        public function createComida(array $data): int {
            $sql = "INSERT INTO comidas (tipo_comida, hora, calorias_totales_comida, proteinas_totales_comida, carbohidratos_totales_comida, grasas_totales_comida, notas) 
                    VALUES (:tipo_comida, :hora, 0, 0, 0, 0, :notas)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':tipo_comida' => $data['tipo_comida'],
                ':hora' => $data['hora'],
                ':notas' => $data['nota']
            ]);
            return (int)$this->pdo->lastInsertId();
        }

        /**
         * Funcion para capturar la comida
         * @param int El id de la comida a buscar.
         * @return array. Devuelve los datos de la comida encontrada
         */

        public function getComidaId(int $id): ?array {
            $sql = "SELECT id_comida FROM comidas WHERE id_comida = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        /**
         * @param int $comidaId. Id de la comida donde se asocia el alimento.
         * @param int $alimentoId. Id del alimento que se asociara.
         * @param float. $cantidad. Flotante de la cantidad en g del alimento
         * @param array $nutricion. Array con los valores nutricionales del alimento.
         * @return void.
         */

        public function asociarAlimento(int $comidaId, int $alimentoId, float $cantidad, array $nutricion): void {
            $sql = "INSERT INTO comida_alimento (id_comida, id_alimento, cantidad, calorias_totales_alimento, proteinas_totales_alimento, carbohidratos_totales_alimento, grasas_totales_alimento)
                    VALUES (:id_comida, :id_alimento, :cantidad, :cal, :prot, :carb, :gras)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_comida' => $comidaId,
                ':id_alimento' => $alimentoId,
                ':cantidad' => $cantidad,
                ':cal' => $nutricion['calorias_totales_alimento'],
                ':prot' => $nutricion['proteinas_totales_alimento'],
                ':carb' => $nutricion['carbohidratos_totales_alimento'],
                ':gras' => $nutricion['grasas_totales_alimento'],
            ]);
        }
        /**
         * Funcion donde agregamos un alimento en la tabla comida_alimento. 
         *  @param int $comidaId. Id de la comida donde se asocia el alimento.
         *  @param int $alimentoId. Id del alimento que se asociara.
         *  @param float. $cantidad. Flotante de la cantidad en g del alimento
         *  @param array $nutricion. Array con los valores nutricionales del alimento.
         *  @return bool. Resultado de la insercion
         */

        public function agregarAlimento(int $comidaId, int $alimentoId, float $cantidad, array $nutricion): bool {
            $stmt = $this->pdo->prepare("SELECT categoria FROM alimentos WHERE id_alimento = :id_alimento");
            $stmt->execute(['id_alimento' => $alimentoId]);
            $cat = $stmt->fetch(PDO::FETCH_ASSOC)['categoria'] ?? null;
            if (!$cat) return false;

            $sql = "INSERT INTO comida_alimento (id_comida, id_alimento, cantidad, calorias_totales_alimento, proteinas_totales_alimento, carbohidratos_totales_alimento, grasas_totales_alimento, categoria)
                    VALUES (:id_comida, :id_alimento, :cantidad, :cal, :prot, :carb, :gras, :categoria)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':id_comida' => $comidaId,
                ':id_alimento' => $alimentoId,
                ':cantidad' => $cantidad,
                ':cal' => $nutricion['calorias_totales_alimento'],
                ':prot' => $nutricion['proteinas_totales_alimento'],
                ':carb' => $nutricion['carbohidratos_totales_alimento'],
                ':gras' => $nutricion['grasas_totales_alimento'],
                ':categoria' => $cat
            ]);
        }

        /**
         * Funcion donde se actualiza la cantidad de los alimentos 
         *  @param int $comidaId. Id de la comida donde se asocia el alimento.
         *  @param int $alimentoId. Id del alimento que se asociara.
         *  @param float. $cantidad. Flotante de la cantidad en g del alimento
         *  @param array $nutricion. Array con los valores nutricionales del alimento.
         *  @return bool. Resultado de la insercion
         */

        public function actualizarCantidadAlimento(int $comidaId, int $alimentoId, float $cantidad, array $nutricion): bool {
            $sql = "UPDATE comida_alimento SET cantidad = :cantidad, calorias_totales_alimento = :cal, proteinas_totales_alimento = :prot, carbohidratos_totales_alimento = :carb, grasas_totales_alimento = :gras WHERE id_comida = :id_comida AND id_alimento = :id_alimento";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':cantidad' => $cantidad,
                ':cal' => $nutricion['calorias_totales_alimento'],
                ':prot' => $nutricion['proteinas_totales_alimento'],
                ':carb' => $nutricion['carbohidratos_totales_alimento'],
                ':gras' => $nutricion['grasas_totales_alimento'],
                ':id_comida' => $comidaId,
                ':id_alimento' => $alimentoId
            ]);
        }

        public function eliminarAlimentoDeComida(int $comidaId, int $alimentoId): bool {
            $stmt = $this->pdo->prepare("DELETE FROM comida_alimento WHERE id_comida = :id AND id_alimento = :al");
            return $stmt->execute([':id' => $comidaId, ':al' => $alimentoId]);
        }

        public function eliminarSuplementoDeComida(int $comidaId, int $suplementoId): bool {
            $stmt = $this->pdo->prepare("DELETE FROM comida_suplemento WHERE id_comida = :id AND id_suplemento = :s");
            return $stmt->execute([':id' => $comidaId, ':s' => $suplementoId]);
        }

        public function actualizarTotalesComida(int $comidaId): void {
            $sql = "UPDATE comidas SET calorias_totales_comida = (SELECT COALESCE(SUM(calorias_totales_alimento),0) FROM comida_alimento WHERE id_comida = :id) + (SELECT COALESCE(SUM(calorias_totales_suplemento),0) FROM comida_suplemento WHERE id_comida = :id),
                                        proteinas_totales_comida = (SELECT COALESCE(SUM(proteinas_totales_alimento),0) FROM comida_alimento WHERE id_comida = :id) + (SELECT COALESCE(SUM(proteinas_totales_suplemento),0) FROM comida_suplemento WHERE id_comida = :id),
                                        carbohidratos_totales_comida = (SELECT COALESCE(SUM(carbohidratos_totales_alimento),0) FROM comida_alimento WHERE id_comida = :id) + (SELECT COALESCE(SUM(carbohidratos_totales_suplemento),0) FROM comida_suplemento WHERE id_comida = :id),
                                        grasas_totales_comida = (SELECT COALESCE(SUM(grasas_totales_alimento),0) FROM comida_alimento WHERE id_comida = :id) + (SELECT COALESCE(SUM(grasas_totales_suplemento),0) FROM comida_suplemento WHERE id_comida = :id)
                    WHERE id_comida = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $comidaId]);
        }
        /**
         * Asocia un equivalente de un alimento. Se pasa el id del alimento equivalente, la cantidad y se asocia a la fila de donde corresponde el alimento.
         * Tiene la misma logica la primera funcion que las otras dos, ya que se buscan 3 equivalentes
         *
         * @param int $comidaId ID de la comida.
         * @param int $alimentoId ID del alimento principal.
         * @param int $id_equivalente ID del alimento equivalente.
         * @param float $cantidad Cantidad del equivalente.
         * @return void
         */

        public function insertarEquivalencia(int $comidaId, int $alimentoId, int $id_equivalente, float $cantidad): void {
            $stmt = $this->pdo->prepare("UPDATE comida_alimento SET id_alimento_equivalente = :eq, cantidad_equivalente = :cant WHERE id_comida = :id AND id_alimento = :al");
            $stmt->execute([':eq' => $id_equivalente, ':cant' => round($cantidad, 2), ':id' => $comidaId, ':al' => $alimentoId]);
        }

        public function insertarEquivalencia1(int $comidaId, int $alimentoId, int $id_equivalente, float $cantidad): void {
            $stmt = $this->pdo->prepare("UPDATE comida_alimento SET id_alimento_equivalente1 = :eq, cantidad_equivalente1 = :cant WHERE id_comida = :id AND id_alimento = :al");
            $stmt->execute([':eq' => $id_equivalente, ':cant' => round($cantidad, 2), ':id' => $comidaId, ':al' => $alimentoId]);
        }

        public function insertarEquivalencia3(int $comidaId, int $alimentoId, int $id_equivalente, float $cantidad): void {
            $stmt = $this->pdo->prepare("UPDATE comida_alimento SET id_alimento_equivalente3 = :eq, cantidad_equivalente3 = :cant WHERE id_comida = :id AND id_alimento = :al");
            $stmt->execute([':eq' => $id_equivalente, ':cant' => round($cantidad, 2), ':id' => $comidaId, ':al' => $alimentoId]);
        }
    }
?>