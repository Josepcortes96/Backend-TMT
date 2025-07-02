<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use PDO;
use Exception;

class UserRepository implements UserRepositoryInterface {
    private PDO $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Valida si existe un centro con el ID dado.
     * @param int $centroId
     * @return bool
     */
    public function validarCentro(int $centroId): bool {
        $stmt = $this->pdo->prepare("SELECT 1 FROM centros WHERE id_centro = :centro_id");
        $stmt->bindParam(':centro_id', $centroId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Crea un nuevo usuario y sus relaciones.
     * @param User $user
     * @return int El ID del usuario creado
     * @throws Exception
     */
    public function create(User $user): int {
        try {
            $this->pdo->beginTransaction();

            if (!$this->validarCentro($user->centroId)) {
                throw new Exception("Centro no válido");
            }

            $sql = "INSERT INTO usuarios (username, nombre, apellidos, password, rol, fecha_creacion, correo, estado, telefono, direccion, fecha_de_nacimiento, ciudad) VALUES (:username, :nombre, :apellidos, :password, :rol, NOW(), :correo, :estado, :telefono, :direccion, :fechaNacimiento, :ciudad)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ':username' => $user->username,
                ':nombre' => $user->nombre,
                ':apellidos' => $user->apellidos,
                ':password' => $user->password,
                ':rol' => $user->rol,
                ':correo' => $user->correo,
                ':estado' => $user->estado,
                ':telefono' => $user->telefono,
                ':direccion' => $user->direccion,
                ':fechaNacimiento' => $user->fechaNacimiento,
                ':ciudad' => $user->ciudad
            ]);

            $userId = (int) $this->pdo->lastInsertId();

            $this->insertUsuarioRol($userId, $user->rol);
            $this->relacionCentroUsuario($userId, $user->centroId, $user->rol);

            $this->pdo->commit();
            return $userId;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Error al crear usuario: " . $e->getMessage());
        }
    }

    /**
     * Obtiene un usuario por su ID.
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function getUser(int $id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new \Exception("Usuario con ID $id no encontrado.");
        }

        return $user;
    }

    /**
     * Obtiene todos los usuarios.
     * @return array
     */
    public function read(): array {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el ID de usuario por nombre.
     * @param string $nombre
     * @return array
     * @throws Exception
     */
    public function getUserName(string $nombre): array {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE nombre = :nombre");
        $stmt->execute([':nombre' => $nombre]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new \Exception("Usuario con nombre $nombre no encontrado.");
        }

        return $user;
    }

    /**
     * Actualiza los datos de un usuario.
     * @param int $id
     * @param User $user
     * @return void
     */
    public function update(int $id, User $user): void {
        $sql = "UPDATE usuarios SET username = :username, nombre = :nombre, apellidos = :apellidos, password = :password, rol = :rol, correo = :correo, estado = :estado, telefono = :telefono, direccion = :direccion, fecha_de_nacimiento = :fechaNacimiento, ciudad = :ciudad WHERE id_usuario = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':username' => $user->username,
            ':nombre' => $user->nombre,
            ':apellidos' => $user->apellidos,
            ':password' => password_hash($user->password, PASSWORD_BCRYPT),
            ':rol' => $user->rol,
            ':correo' => $user->correo,
            ':estado' => $user->estado,
            ':telefono' => $user->telefono,
            ':direccion' => $user->direccion,
            ':fechaNacimiento' => $user->fechaNacimiento,
            ':ciudad' => $user->ciudad,
            ':id' => $id
        ]);
    }

    /**
     * Elimina un usuario por su ID.
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Desactiva un usuario (cambia su estado a 'inactivo').
     * @param int $id
     * @return bool
     */
    public function desactivar(int $id): bool {
        // Obtener estado actual
        $stmt = $this->pdo->prepare("SELECT estado FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        $estadoActual = $stmt->fetchColumn();

        if ($estadoActual === false) {
            throw new \Exception("Usuario con ID $id no encontrado.");
        }

        // Determinar nuevo estado
        $nuevoEstado = strtolower($estadoActual) === 'activo' ? 'inactivo' : 'activo';

        // Actualizar estado
        $stmt = $this->pdo->prepare("UPDATE usuarios SET estado = :estado WHERE id_usuario = :id");
        return $stmt->execute([
            ':estado' => $nuevoEstado,
            ':id' => $id
        ]);
    }


    /**
     * Obtiene el rol de un usuario por su ID.
     * @param int $id
     * @return string
     */
    public function getRol(int $id): string {
        $stmt = $this->pdo->prepare("SELECT rol FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        return (string) $stmt->fetchColumn();
    }

    /**
     * Inserta el usuario en la tabla correspondiente a su rol.
     * @param int $userId
     * @param string $rol
     * @return int
     */
    public function insertUsuarioRol(int $userId, string $rol): int {
        $tabla = $this->getTablaUsuario($rol);
        $stmt = $this->pdo->prepare("INSERT INTO $tabla (id_usuario) VALUES (:id_usuario)");
        $stmt->execute([':id_usuario' => $userId]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Relaciona un usuario con un centro según su rol.
     * @param int $userId
     * @param int $centroId
     * @param string $rol
     * @return void
     * @throws Exception
     */
    public function relacionCentroUsuario(int $userId, int $centroId, string $rol): void {
        $tablaCentro = match ($rol) {
            'Cliente' => 'centro_cliente',
            'Preparador' => 'centro_preparador',
            'Propietario' => 'centro_propietario',
            default => throw new Exception("Rol no válido: $rol")
        };

        $tablaUsuario = $this->getTablaUsuario($rol);
        $col = match ($rol) {
            'Cliente' => 'cliente',
            'Preparador' => 'preparador',
            'Propietario' => 'propietario',
            default => throw new Exception("Rol no válido: $rol")
        };

        $stmt = $this->pdo->prepare("INSERT INTO $tablaCentro (id_centro, id_$col) VALUES (:centro_id, (SELECT id_$col FROM $tablaUsuario WHERE id_usuario = :id_usuario))");
        $stmt->execute([
            ':centro_id' => $centroId,
            ':id_usuario' => $userId
        ]);
    }

    /**
     * Elimina un usuario de la tabla correspondiente a su rol.
     * @param int $id
     * @param string $rol
     * @return void
     */
    public function eliminarUsuarioTabla(int $id, string $rol): void {
        $tabla = $this->getTablaUsuario($rol);
        $stmt = $this->pdo->prepare("DELETE FROM $tabla WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Elimina la relación entre un usuario y un centro según su rol.
     * @param int $id
     * @param string $rol
     * @return void
     * @throws Exception
     */
    public function eliminarRelacionCentroRol(int $id, string $rol): void {
        $col = match ($rol) {
            'Cliente' => 'cliente',
            'Preparador' => 'preparador',
            'Propietario' => 'propietario',
            default => throw new Exception("Rol no válido")
        };
        $tabla = "centro_{$col}";
        $stmt = $this->pdo->prepare("DELETE FROM $tabla WHERE id_$col = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Elimina la relación entre un usuario y una dieta según su rol.
     * @param int $id
     * @param string $rol
     * @return void
     * @throws Exception
     */
    public function eliminarRelacionDietaRol(int $id, string $rol): void {
        $col = match ($rol) {
            'Cliente' => 'cliente',
            'Preparador' => 'preparador',
            'Propietario' => 'propietario',
            default => throw new Exception("Rol no válido")
        };
        $tabla = "dieta_{$col}";
        $stmt = $this->pdo->prepare("DELETE FROM $tabla WHERE id_$col = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Captura el ID del centro asociado a un usuario según su rol.
     * @param int $id
     * @param string $rol
     * @return int|null
     * @throws Exception
     */
    public function capturarCentroId(int $id, string $rol): ?int {
        $tablaUsuario = $this->getTablaUsuario($rol);
        $col = match ($rol) {
            'Cliente' => 'cliente',
            'Preparador' => 'preparador',
            'Propietario' => 'propietario',
            default => throw new Exception("Rol no válido")
        };

        $stmt = $this->pdo->prepare("SELECT id_centro FROM centro_{$col} WHERE id_{$col} = (SELECT id_{$col} FROM {$tablaUsuario} WHERE id_usuario = :id)");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() ?: null;
    }

    /**
     * Captura el ID de la dieta asociada a un usuario según su rol.
     * @param int $id
     * @param string $rol
     * @return int|null
     * @throws Exception
     */
    public function capturarDietaId(int $id, string $rol): ?int {
        $tablaUsuario = $this->getTablaUsuario($rol);
        $col = match ($rol) {
            'Cliente' => 'cliente',
            'Preparador' => 'preparador',
            'Propietario' => 'propietario',
            default => throw new Exception("Rol no válido")
        };

        $stmt = $this->pdo->prepare("SELECT id_dieta FROM dieta_{$col} WHERE id_{$col} = (SELECT id_{$col} FROM {$tablaUsuario} WHERE id_usuario = :id)");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() ?: null;
    }

    /**
     * Relaciona un usuario con una dieta según su rol.
     * @param int $userId
     * @param int|null $dietaId
     * @param string $rol
     * @return void
     * @throws Exception
     */
    public function relacionDietaUsuario(int $userId, ?int $dietaId, string $rol): void {
        if ($dietaId === null) return;

        $tablaUsuario = $this->getTablaUsuario($rol);
        $col = match ($rol) {
            'Cliente' => 'cliente',
            'Preparador' => 'preparador',
            'Propietario' => 'propietario',
            default => throw new Exception("Rol no válido")
        };

        $tabla = "dieta_{$col}";
        $stmt = $this->pdo->prepare("INSERT INTO $tabla (id_dieta, id_$col) VALUES (:dieta_id, (SELECT id_$col FROM $tablaUsuario WHERE id_usuario = :id_usuario))");
        $stmt->execute([':dieta_id' => $dietaId, ':id_usuario' => $userId]);
    }

    /**
     * Obtiene el nombre de la tabla de usuario según el rol.
     * @param string $rol
     * @return string
     * @throws Exception
     */
    private function getTablaUsuario(string $rol): string {
        return match ($rol) {
            'Cliente' => 'clientes',
            'Preparador' => 'preparadores',
            'Propietario' => 'propietarios',
            default => throw new Exception("Rol no válido: $rol")
        };
    }

    /**
     * Obtiene todos los usuarios de un centro.
     * @param int $centroId
     * @return array
     */
    public function getUsersByCentro(int $centroId): array {
        $stmt = $this->pdo->prepare("
            SELECT u.*, c.nombre AS nombre_centro
            FROM usuarios u
            INNER JOIN clientes cli ON u.id_usuario = cli.id_usuario
            INNER JOIN centro_cliente cc ON cli.id_cliente = cc.id_cliente
            INNER JOIN centros c ON cc.id_centro = c.id_centro
            WHERE c.id_centro = :centro_id
        ");
        $stmt->execute([':centro_id' => $centroId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
