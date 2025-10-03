<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DatoRepositoryInterface;
use PDO;
use Exception;

class DatoRepository implements DatoRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Inserta un nuevo registro en la tabla datos.
     *
     * @param array $data Clave => valor con los campos y valores a insertar.
     *
     * @return int ID autogenerado del dato insertado.
     *
     * @throws Exception Si falla la inserción.
     */

    public function createDato(array $data): int
    {
        $campos = array_keys($data);
        $placeholders = array_map(fn($campo) => ':' . $campo, $campos);

        $sql = "INSERT INTO datos (" . implode(',', $campos) . ")
                VALUES (" . implode(',', $placeholders) . ")";

        $stmt = $this->pdo->prepare($sql);

        if (!$stmt->execute($data)) {
            throw new Exception("Error al insertar el dato.");
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Obtiene un registro de datos por su ID.
     *
     * @param int $id_dato Identificador único del dato.
     *
     * @return array Registro completo del dato.
     */
    public function getDatoById(int $id_dato): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM datos WHERE id_dato = :id");
        $stmt->execute([':id' => $id_dato]);
        $dato = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dato;
    }

    /**
     * Busca un dato por su nombre asociado a un usuario específico.
     *
     * @param string $nombre     Nombre del dato.
     * @param int    $idUsuario  Identificador del usuario propietario del dato.
     *
     * @return array Registro encontrado o array vacío si no existe.
     */

    public function getDatoByNombre(string $nombre, int $idUsuario): array
    {
        $sql = "SELECT * FROM datos d
        WHERE d.nombre = :nombre AND d.id_usuario = :id_usuario";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':id_usuario' => $idUsuario
        ]);

        $dato = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dato;

    }

    /**
     * Actualiza un dato existente con los valores proporcionados.
     *
     * @param int   $id_dato ID del dato a actualizar.
     * @param array $data    Clave => valor con los campos a modificar.
     *
     * @return bool True si la actualización fue exitosa, False en caso contrario.
     */

    public function actualizarDato(int $id_dato, array $data): bool
    {
        $fields = array_map(fn($campo) => "$campo = :$campo", array_keys($data));
        $sql = "UPDATE datos SET " . implode(', ', $fields) . " WHERE id_dato = :id_dato";

        $stmt = $this->pdo->prepare($sql);
        $data['id_dato'] = $id_dato;

        return $stmt->execute($data);
    }

    /**
     * Elimina un dato de la base de datos por su ID.
     *
     * @param int $id_dato Identificador único del dato.
     *
     * @return bool True si la eliminación fue exitosa.
     */
    public function deleteDato(int $id_dato): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM datos WHERE id_dato = :id");
        return $stmt->execute([':id' => $id_dato]);
    }

    /**
     * Obtiene todos los registros de la tabla datos.
     *
     * @return array[] Lista de registros en formato asociativo.
     */

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM datos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el peso actual registrado de un usuario.
     *
     * @param int $id_usuario Identificador del usuario.
     *
     * @return float Peso del usuario.
     *
     * @throws Exception Si no se encuentra un dato con peso para el usuario.
     */

    public function getPeso(int $id_usuario): float{
        $query = "
            SELECT d.peso
            FROM datos d
            WHERE d.id_usuario = :id_usuario
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || !isset($result['peso'])) {
            throw new Exception("No se encontró el peso para el usuario con ID $id_usuario.");
        }

        return (float) $result['peso'];
    }

    
    /**
     * Obtiene los últimos 3 controles registrados de un usuario.
     *
     * @param int $idUsuario Identificador del usuario.
     *
     * @return array[] Lista de registros con sus campos.
     */

    public function getUltimosControles(int $idUsuario): array
    {
          $sql = "SELECT * FROM datos 
            WHERE id_usuario = :id_usuario 
            ORDER BY id_dato DESC 
            LIMIT 3";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id_usuario' => $idUsuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        
    /**
     * Obtiene todos los controles de un usuario mostrando solo ID y nombre.
     *
     * @param int $idUsuario Identificador del usuario.
     *
     * @return array[] Lista de controles en formato:
     * [
     *   @type int    $id_dato
     *   @type string $nombre
     * ]
     */

     public function getTodosControles(int $idUsuario): array
    {
          $sql = "SELECT id_dato, nombre FROM datos 
            WHERE id_usuario = :id_usuario 
            ORDER BY id_dato DESC 
            ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['id_usuario' => $idUsuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


        /**
     * Obtiene el último control registrado de un usuario.
     *
     * @param int $idUsuario Identificador del usuario.
     *
     * @return array|null Registro completo del último control o null si no existe.
     */

    public function getUltimoControlPorId(int $idUsuario): ?array
    {
        $sql = "SELECT * FROM datos 
                WHERE id_usuario = :id_usuario 
                ORDER BY id_dato DESC 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


    public function getInformeDato(int $idUsuario, int $id_dato): ?array
    {
        $sql = "
                SELECT 
                    d.nombre as nombre_control,
                    d.edad,
                    d.cuello, 
                    d.brazo, 
                    d.cintura, 
                    d.abdomen, 
                    d.cadera,
                    d.muslo, 
                    d.imc, 
                    d.indice_masa_magra, 
                    d.triceps, 
                    d.subescapular, 
                    d.abdomen_pliegue, 
                    d.supra_iliaco, 
                    d.muslo_pliegue, 
                    d.humero_biepicondileo, 
                    d.femur_bicondileo, 
                    d.muneca_estiloideo, 
                    d.complex_osea, 
                    d.porcentaje_graso_estimado_pliegues,
                    d.peso_oseo_rocha, 
                    d.peso_residual,
                    d.peso_extracelular,
                    d.peso_intracelular,
                    d.porcentaje_extracelular,
                    d.porcentaje_intracelular,
                    d.porcentaje_residual,
                    d.kg_masa_magra, 
                    d.kg_grasa,
                    d.peso,
                    u.nombre,
                    u.apellidos,
                    u.telefono,
                    u.fecha_de_nacimiento,
                    u.correo
                FROM datos d
                INNER JOIN usuarios u ON d.id_usuario = u.id_usuario
                WHERE d.id_dato = :id_dato AND d.id_usuario = :id_usuario";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id_dato' => $id_dato,
                ':id_usuario' => $idUsuario
            ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }


        public function getInformeDatoHistorico(int $idUsuario): ?array
    {
        $sql = "
                
                SELECT
                d.nombre as nombre_control,
                    d.edad,
                    d.cuello, 
                    d.brazo, 
                    d.cintura, 
                    d.abdomen, 
                    d.cadera,
                    d.muslo, 
                    d.imc, 
                    d.indice_masa_magra, 
                    d.triceps, 
                    d.subescapular, 
                    d.abdomen_pliegue, 
                    d.supra_iliaco, 
                    d.muslo_pliegue, 
                    d.humero_biepicondileo, 
                    d.femur_bicondileo, 
                    d.muneca_estiloideo, 
                    d.complex_osea, 
                    d.porcentaje_graso_estimado_pliegues,
                    d.peso_oseo_rocha, 
                    d.peso_residual,
                    d.peso_extracelular,
                    d.peso_intracelular, 
                    d.kg_masa_magra, 
                    d.kg_grasa,
                    d.peso,
                    d.fecha
                FROM datos d
                WHERE id_usuario = :id_usuario
                ORDER BY fecha DESC;";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([ ':id_usuario' => $idUsuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

}
?>

