<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use PDO;
use Exception;

class UserRepository implements UserRepositoryInterface {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function validarCentro(int $centroId): bool {
        $stmt = $this->pdo->prepare("SELECT 1 FROM centros WHERE id_centro = :centro_id");
        $stmt->bindParam(':centro_id', $centroId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function create(User $user): int {
        try {
            $this->pdo->beginTransaction();

            if (!$this->validarCentro($user->centroId)) {
                throw new Exception("Centro no válido");
            }

            $sql = "INSERT INTO usuarios (username, nombre, apellidos, password, rol, fecha_creacion, correo, estado, telefono, direccion, fecha_de_nacimiento, ciudad) VALUES (:username, :nombre, :apellidos, :password, :rol, NOW(), :correo, :estado, :telefono, :direccion, :fechaNacimiento, :ciudad)";
            $stmt = $this->pdo->prepare($sql);
            $hashed = password_hash($user->password, PASSWORD_BCRYPT);

            $stmt->execute([
                ':username' => $user->username,
                ':nombre' => $user->nombre,
                ':apellidos' => $user->apellidos,
                ':password' => $hashed,
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

    public function read(): array {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser(int $id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function desactivar(int $id): bool {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET estado = 'inactivo' WHERE id_usuario = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getRol(int $id): string {
        $stmt = $this->pdo->prepare("SELECT rol FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
        return (string) $stmt->fetchColumn();
    }

    public function insertUsuarioRol(int $userId, string $rol): int {
        $tabla = $this->getTablaUsuario($rol);
        $stmt = $this->pdo->prepare("INSERT INTO $tabla (id_usuario) VALUES (:id_usuario)");
        $stmt->execute([':id_usuario' => $userId]);
        return (int) $this->pdo->lastInsertId();
    }

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

    public function eliminarUsuarioTabla(int $id, string $rol): void {
        $tabla = $this->getTablaUsuario($rol);
        $stmt = $this->pdo->prepare("DELETE FROM $tabla WHERE id_usuario = :id");
        $stmt->execute([':id' => $id]);
    }

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

    private function getTablaUsuario(string $rol): string {
        return match ($rol) {
            'Cliente' => 'clientes',
            'Preparador' => 'preparadores',
            'Propietario' => 'propietarios',
            default => throw new Exception("Rol no válido: $rol")
        };
    }
}
