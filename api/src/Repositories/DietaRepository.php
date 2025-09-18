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

            /**
             * Crea una nueva dieta en la base de datos.
             *
             * @param string|null $nombre              Nombre de la dieta (opcional).
             * @param string|null $descripcion         Descripción de la dieta (opcional).
             * @param int         $id_usuario          Identificador del usuario creador.
             * @param int         $id_dato             Identificador del dato asociado.
             * @param float       $calorias_dieta      Calorías totales de la dieta.
             * @param float       $proteinas_dieta     Proteínas totales de la dieta.
             * @param float       $grasas_dieta        Grasas totales de la dieta.
             * @param float       $carbohidratos_dieta Carbohidratos totales de la dieta.
             * @param string|null $fecha_creacion      Fecha de creación (opcional, por defecto fecha actual).
             *
             * @return int ID de la dieta recién creada.
             *
             * @throws Exception Si ocurre un error en la inserción o no se puede obtener el ID generado.
             */

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

        

        /**
         * Verifica si existe una dieta en la base de datos por su ID.
         *
         * @param int $id_dieta Identificador único de la dieta.
         *
         * @return bool True si existe, False si no.
         */       
        public function getDietaById($id_dieta):bool {
            $sql = "SELECT COUNT(*) FROM dietas WHERE id_dieta = :id_dieta";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_dieta' => $id_dieta]);
            return $stmt->fetchColumn() > 0;
        }


        /**
         * Obtiene los datos básicos de una dieta por su ID.
         *
         * @param int $id_dieta Identificador único de la dieta.
         *
         * @return array {
         *     @type string $nombre
         *     @type string $descripcion
         *     @type float  $calorias_dieta
         *     @type float  $proteinas_dieta
         *     @type float  $grasas_dieta
         *     @type float  $carbohidratos_dieta
         * }
         */

        public function getDieta($id_dieta):array {
            $sql = "SELECT nombre, descripcion,calorias_dieta,proteinas_dieta,grasas_dieta,carbohidratos_dieta FROM dietas WHERE id_dieta = :id_dieta";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_dieta', $id_dieta, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Elimina una dieta de la base de datos por su ID.
         *
         * @param int $id_dieta Identificador único de la dieta.
         *
         * @return bool True si la eliminación fue exitosa, False si falló.
         */

        public function deleteDieta($id_dieta):bool {
            $sql = "DELETE FROM dietas WHERE id_dieta = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id_dieta);
            return $stmt->execute();
        }

        /**
         * Asocia una comida existente a una dieta, evitando duplicados.
         *
         * @param int $id_dieta Identificador único de la dieta.
         * @param int $id_comida Identificador único de la comida.
         *
         * @return void
         *
         * @throws Exception Si los IDs no son enteros o ocurre un error en la inserción.
         */


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
        /**
         * Actualiza los datos principales de una dieta.
         *
         * @param int    $id_dieta          Identificador único de la dieta.
         * @param string $nombre            Nombre de la dieta.
         * @param string $descripcion       Descripción de la dieta.
         * @param float  $proteinas_dieta   Proteínas totales.
         * @param float  $grasas_dieta      Grasas totales.
         * @param float  $carbohidratos_dieta Carbohidratos totales.
         *
         * @return array {
         *     @type string $message Mensaje de éxito.
         *     @type string $error   Mensaje de error si no se actualizó.
         * }
         */

        public function actualizarDieta($id_dieta, $nombre,$descripcion,$proteinas_dieta, $grasas_dieta, $carbohidratos_dieta):array {
            $stmt = $this->pdo->prepare("
                UPDATE dietas
                SET
                nombre =:nombre,
                descripcion =:descripcion,
                proteinas_dieta = :proteinas_dieta,
                    grasas_dieta = :grasas_dieta,
                    carbohidratos_dieta = :carbohidratos_dieta
                WHERE id_dieta = :id_dieta
            ");
            $stmt->execute([
                'id_dieta' => $id_dieta,
                'nombre' => $nombre,
                'descripcion' => $descripcion,
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

        /**
         * Inserta una relación entre una dieta y un usuario según su rol (Propietario o Preparador).
         *
         * @param int    $id_dieta  Identificador único de la dieta.
         * @param int    $id_usuario Identificador único del usuario.
         * @param string $rol        Rol del usuario (Propietario o Preparador).
         *
         * @return array {
         *     @type string $message Mensaje de éxito.
         *     @type string $warning Advertencia si ya existe la relación.
         *     @type string $error   Error en caso de rol no válido o fallo en la BD.
         * }
         */

        public function insertDietaRol(int $id_dieta, int $id_usuario, string $rol): array {
            try {
                if ($rol === 'Propietario') {
                    $stmt = $this->pdo->prepare("SELECT id_propietario FROM propietarios WHERE id_usuario = ?");
                    $stmt->execute([$id_usuario]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$row) {
                        return ["error" => "No se encontró el propietario con id_usuario = $id_usuario"];
                    }

                    $id_propietario = $row['id_propietario'];

                    // Verifica si ya existe
                    $check = $this->pdo->prepare("SELECT COUNT(*) FROM dieta_propietario WHERE id_dieta = ? AND id_propietario = ?");
                    $check->execute([$id_dieta, $id_propietario]);

                    if ((int)$check->fetchColumn() === 0) {
                        $insert = $this->pdo->prepare("INSERT INTO dieta_propietario (id_dieta, id_propietario) VALUES (?, ?)");
                        $insert->execute([$id_dieta, $id_propietario]);
                        return ["message" => "Dieta asignada al propietario correctamente"];
                    } else {
                        return ["warning" => "La dieta ya está asignada a este propietario"];
                    }

                } elseif ($rol === 'Preparador') {
                    $stmt = $this->pdo->prepare("SELECT id_preparador FROM preparadores WHERE id_usuario = ?");
                    $stmt->execute([$id_usuario]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$row) {
                        return ["error" => "No se encontró el preparador con id_usuario = $id_usuario"];
                    }

                    $id_preparador = $row['id_preparador'];

                    // Verifica si ya existe
                    $check = $this->pdo->prepare("SELECT COUNT(*) FROM dieta_preparador WHERE id_dieta = ? AND id_preparador = ?");
                    $check->execute([$id_dieta, $id_preparador]);

                    if ((int)$check->fetchColumn() === 0) {
                        $insert = $this->pdo->prepare("INSERT INTO dieta_preparador (id_dieta, id_preparador) VALUES (?, ?)");
                        $insert->execute([$id_dieta, $id_preparador]);
                        return ["message" => "Dieta asignada al preparador correctamente"];
                    } else {
                        return ["warning" => "La dieta ya está asignada a este preparador"];
                    }

                } else {
                    return ["error" => "Rol no válido. Solo se admite 'Propietario' o 'Preparador'"];
                }
            } catch (Exception $e) {
                return ["error" => "Error al asociar dieta según el rol: " . $e->getMessage()];
            }
        }



        /**
         * Obtiene todas las dietas creadas por un usuario específico.
         *
         * @param int $id_usuario Identificador único del usuario.
         *
         * @return array[] Lista de dietas con estructura:
         * [
         *     @type int    $id_dieta
         *     @type string $nombre
         * ]
         */

        public function getDietasPorUsuario(int $id_usuario): array {
            $sql = "SELECT id_dieta, nombre FROM dietas WHERE id_usuario = :id_usuario";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_usuario' => $id_usuario]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Obtiene información de una dieta junto con su dato asociado.
         *
         * @param int $id_dieta Identificador único de la dieta.
         *
         * @return array {
         *     @type int    $id_dieta
         *     @type string $fecha_creacion
         *     @type int    $id_dato
         *     @type string $nombre_dato
         * }
         */

        public function getDietaConDato(int $id_dieta): array {
            $sql = "
                SELECT 
                    d.id_dieta,
                    d.fecha_creacion,
                    d.id_dato,
                    datos.nombre AS nombre_dato
                FROM dietas d
                INNER JOIN datos ON datos.id_dato = d.id_dato
                WHERE d.id_dieta = :id_dieta
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_dieta', $id_dieta, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC); // Una sola fila
        }

        /**
         * Genera un informe completo de una dieta con todos sus detalles:
         * datos, usuario creador, comidas, alimentos y asignaciones a preparador/propietario.
         *
         * @param int $id_dieta Identificador único de la dieta.
         *
         * @return array[] Lista de filas que contienen información agregada de dieta, comidas y alimentos.
         */
        
        public function getInformeDieta(int $id_dieta) : array{
            $sql = "
                SELECT 
                    d.nombre AS nombre_dieta,
                    d.descripcion,
                    d.fecha_creacion,
                    d.calorias_dieta,
                    d.proteinas_dieta,
                    d.grasas_dieta,
                    d.carbohidratos_dieta,

                    u.nombre AS nombre_usuario,
                    u.apellidos AS apellido_usuario,

                    c.id_comida,
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


                    up.nombre AS nombre_preparador,
                    up.apellidos AS apellido_preparador,
                    uo.nombre AS nombre_propietario,
                    uo.apellidos AS apellido_propietario

                FROM dietas d


                INNER JOIN datos da ON d.id_dato = da.id_dato
                INNER JOIN usuarios u ON da.id_usuario = u.id_usuario


                LEFT JOIN dieta_comida dc ON d.id_dieta = dc.id_dieta
                LEFT JOIN comidas c ON dc.id_comida = c.id_comida
                LEFT JOIN comida_alimento ca ON c.id_comida = ca.id_comida


                LEFT JOIN alimentos a ON ca.id_alimento = a.id_alimento
                LEFT JOIN alimentos ae ON ca.id_alimento_equivalente = ae.id_alimento
                LEFT JOIN alimentos ae1 ON ca.id_alimento_equivalente1 = ae1.id_alimento
                LEFT JOIN alimentos ae3 ON ca.id_alimento_equivalente3 = ae3.id_alimento
                LEFT JOIN alimentos ae4 ON ca.id_alimento_equivalente4 = ae4.i_alimento
                LEFT JOIN alimentos ae5 ON ca.id_alimento_equivalente5 = ae5.id_alimento
                LEFT JOIN alimentos ae6 ON ca.id_alimento_equivalente6 = ae6.id_alimento
                LEFT JOIN alimentos ae7 ON ca.id_alimento_equivalente7 = ae7.id_alimento
                LEFT JOIN alimentos ae8 ON ca.id_alimento_equivalente8 = ae8.id_alimento
                LEFT JOIN alimentos ae9 ON ca.id_alimento_equivalente9 = ae9.id_alimento
                LEFT JOIN alimentos ae10 ON ca.id_alimento_equivalente10 = ae10.id_alimento



                LEFT JOIN dieta_preparador dp ON d.id_dieta = dp.id_dieta
                LEFT JOIN preparadores p ON dp.id_preparador = p.id_preparador
                LEFT JOIN usuarios up ON p.id_usuario = up.id_usuario


                LEFT JOIN dieta_propietario dpo ON d.id_dieta = dpo.id_dieta
                LEFT JOIN propietarios po ON dpo.id_propietario = po.id_propietario
                LEFT JOIN usuarios uo ON po.id_usuario = uo.id_usuario

                WHERE d.id_dieta = :id_dieta;
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_dieta', $id_dieta, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        }

}
?>