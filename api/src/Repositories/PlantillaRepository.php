<?php
namespace App\Repositories;
use App\Repositories\Interfaces\PlantillaRepositoryInterface;
use PDO;
use Exception;

class PlantillaRepository implements PlantillaRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

            /**
             * Crea una nueva dieta en la base de datos.
             *
             * @param string|null $nombre              Nombre de la plantilla (opcional).
             * @param int         $id_centro          Identificador del usuario creador.
             * @param string|null $fecha_creacion      Fecha de creación (opcional, por defecto fecha actual).
             *
             * @return int ID de la dieta recién creada.
             *
             * @throws Exception Si ocurre un error en la inserción o no se puede obtener el ID generado.
             */

            public function createPlantilla(?string $nombre,  int $id_usuario, int $id_centro, ?string $fecha_creacion = null): int{
                    
                        $sql = "INSERT INTO plantillas (
                                nombre, id_usuario, id_centro, fecha_creacion
                            ) VALUES (
                                :nombre, :id_usuario, :id_centro, :fecha_creacion
                            )";

                        $stmt = $this->pdo->prepare($sql);

                        try {
                            $stmt->execute([
                                ':nombre' => $nombre,
                                ':id_usuario' => $id_usuario,
                                ':id_centro' => $id_centro,
                                ':fecha_creacion' => $fecha_creacion ?? date('Y-m-d')
                            ]);
                        } catch (Exception $e) {
                            throw new Exception("Error al crear la plantilla: " . $e->getMessage());
                        }

                        $id_plantilla = $this->pdo->lastInsertId();

                        if (!$id_plantilla) {
                            throw new Exception("No se pudo obtener el ID de la plantilla recién creada.");
                        }

                        return $id_plantilla;
                }

        

    
        /**
         * Obtiene los datos básicos de una dieta por su ID.
         *
         * @param int $id_dieta Identificador único de la dieta.
         *
         * @return array {
         *      @type int   $id_plantilla
         *      @type string $nombre
         *    
         * }
         */

        public function getPlantilla(int $id_plantilla):array {
            $sql = "SELECT id_plantilla, nombre  FROM plantillas WHERE id_plantilla = :id_plantilla";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_plantilla', $id_plantilla, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        }

        /**
         * Elimina una plantilla de la base de datos por su ID.
         *
         * @param int $id_plantilla Identificador único de la plantilla.
         *
         * @return bool True si la eliminación fue exitosa, False si falló.
         */

        public function deletePlantilla(int $id_plantilla):bool {
            $sql = "DELETE FROM plantillas WHERE id_plantilla = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id_plantilla);
            return $stmt->execute();
        }

        /**
         * Asocia una comida existente a una dieta, evitando duplicados.
         *
         * @param int $id_plantilla Identificador único de la plantilla.
         * @param int $id_comida Identificador único de la comida.
         *
         * @return void
         *
         * @throws Exception Si los IDs no son enteros o ocurre un error en la inserción.
         */


        public function asociarComidaPlantilla(int $id_plantilla, int $id_comida):void {
            if (!is_int($id_plantilla) || !is_int($id_comida)) {
                throw new Exception("Los IDs deben ser números enteros.");
            }

            $check = $this->pdo->prepare("SELECT COUNT(*) FROM plantilla_comida WHERE id_plantilla = :id_plantilla AND id_comida = :id_comida");
            $check->execute([
                ':id_plantilla' => $id_plantilla,
                ':id_comida' => $id_comida
            ]);

            if ((int)$check->fetchColumn() > 0) {
                return;
            }

            $sql = "INSERT INTO plantilla_comida (id_plantilla, id_comida) VALUES (:id_plantilla, :id_comida)";
            $stmt = $this->pdo->prepare($sql);

            try {
                $stmt->execute([
                    ':id_plantilla' => $id_plantilla,
                    ':id_comida' => $id_comida
                ]);
            } catch (Exception $e) {
                throw new Exception("Error al asociar comida a plantilla: " . $e->getMessage());
            }
        }

           /**
         * Obtiene todas las dietas creadas por un usuario específico.
         *
         * @param int $id_usuario Identificador único del usuario.
         *
         * @return array[] Lista de dietas con estructura:
         * [
         *     @type int    $id_plantilla
         *     @type string $nombre
         * ]
         */

        public function getPlantillaPorCentro(int $id_centro): array {
            $sql = "SELECT id_plantilla, nombre FROM plantillas WHERE id_centro = :id_centro";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_centro' => $id_centro]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        
         public function getInformePlantilla(int $id_plantilla) : array{
            $sql = "
               SELECT 
                    p.nombre AS nombre_plantilla,
                    p.fecha_creacion,

                    pc.id_comida,
                    c.hora,
                    c.tipo_comida,
                    c.notas,
                    c.calorias_totales_comida,
                    c.proteinas_totales_comida,
                    c.grasas_totales_comida,
                    c.carbohidratos_totales_comida,

                    ca.categoria,
                    ca.cantidad,
                    ca.cantidad_equivalente,
                    ca.cantidad_equivalente1,
                    ca.cantidad_equivalente3,
                    ca.cantidad_equivalente4,
                    ca.cantidad_equivalente5,
                    ca.cantidad_equivalente6,
                    ca.cantidad_equivalente7,
                    ca.cantidad_equivalente8,
                    ca.cantidad_equivalente9,
                    ca.cantidad_equivalente10,

                    ca.id_alimento,
                    ca.id_alimento_equivalente,
                    ca.id_alimento_equivalente1,
                    ca.id_alimento_equivalente3,
                    ca.id_alimento_equivalente4,
                    ca.id_alimento_equivalente5,
                    ca.id_alimento_equivalente6,
                    ca.id_alimento_equivalente7,
                    ca.id_alimento_equivalente8,
                    ca.id_alimento_equivalente9,
                    ca.id_alimento_equivalente10,

                    a.nombre AS nombre_alimento,
                    ae.nombre AS nombre_alimento_equivalente,
                    ae1.nombre AS nombre_alimento_equivalente1,
                    ae3.nombre AS nombre_alimento_equivalente3,
                    ae4.nombre AS nombre_alimento_equivalente4,
                    ae5.nombre AS nombre_alimento_equivalente5,
                    ae6.nombre AS nombre_alimento_equivalente6,
                    ae7.nombre AS nombre_alimento_equivalente7,
                    ae8.nombre AS nombre_alimento_equivalente8,
                    ae9.nombre AS nombre_alimento_equivalente9,
                    ae10.nombre AS nombre_alimento_equivalente10

                FROM plantillas p

                LEFT JOIN plantilla_comida pc ON p.id_plantilla = pc.id_plantilla
                LEFT JOIN comidas c ON pc.id_comida = c.id_comida
                LEFT JOIN comida_alimento ca ON c.id_comida = ca.id_comida

                LEFT JOIN alimentos a ON ca.id_alimento = a.id_alimento
                LEFT JOIN alimentos ae ON ca.id_alimento_equivalente = ae.id_alimento
                LEFT JOIN alimentos ae1 ON ca.id_alimento_equivalente1 = ae1.id_alimento
                LEFT JOIN alimentos ae3 ON ca.id_alimento_equivalente3 = ae3.id_alimento
                LEFT JOIN alimentos ae4 ON ca.id_alimento_equivalente4 = ae4.id_alimento
                LEFT JOIN alimentos ae5 ON ca.id_alimento_equivalente5 = ae5.id_alimento
                LEFT JOIN alimentos ae6 ON ca.id_alimento_equivalente6 = ae6.id_alimento
                LEFT JOIN alimentos ae7 ON ca.id_alimento_equivalente7 = ae7.id_alimento
                LEFT JOIN alimentos ae8 ON ca.id_alimento_equivalente8 = ae8.id_alimento
                LEFT JOIN alimentos ae9 ON ca.id_alimento_equivalente9 = ae9.id_alimento
                LEFT JOIN alimentos ae10 ON ca.id_alimento_equivalente10 = ae10.id_alimento

                WHERE p.id_plantilla = :id_plantilla;
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_plantilla', $id_plantilla, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        }




   

       
      
}
?>