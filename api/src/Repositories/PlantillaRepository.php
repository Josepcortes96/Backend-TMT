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



   

       
      
}
?>