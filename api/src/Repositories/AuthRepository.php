<?php

    namespace App\Repositories;

    use App\Repositories\Interfaces\AuthRepositoryInterface;
    use PDO;
    use Exception;

    class AuthRepository implements AuthRepositoryInterface {
        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        public function verificarCredenciales(string $username, string $password): int {
            $stmt = $this->pdo->prepare("SELECT id_usuario, password FROM usuarios WHERE username = :username");
            $stmt->execute([':username' => $username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception("Credenciales inválidas.");
            }

            return (int) $user['id_usuario'];
        }
        
          public function registrarLogin(int $id_usuario, string $ip, string $userAgent, string $jwtHash): void {
            $stmt = $this->pdo->prepare("CALL sp_log_user_login(:id_usuario, :ip, :userAgent, :jwtHash)");
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':ip' => $ip,
                ':userAgent' => $userAgent,
                ':jwtHash' => $jwtHash
            ]);
        }

        public function obtenerRolYCentro(int $id_usuario): array {
            $stmt = $this->pdo->prepare("
               SELECT 
                    u.rol,
                    CASE
                        WHEN u.rol = 'Cliente' THEN cc.id_centro
                        WHEN u.rol = 'Preparador' THEN pc.id_centro
                        WHEN u.rol = 'Propietario' THEN prc.id_centro
                        ELSE NULL
                    END AS id_centro
                FROM usuarios u
                LEFT JOIN clientes c ON u.id_usuario = c.id_usuario
                LEFT JOIN centro_cliente cc ON c.id_cliente = cc.id_cliente

                LEFT JOIN preparadores p ON u.id_usuario = p.id_usuario
                LEFT JOIN centro_preparador pc ON p.id_preparador = pc.id_preparador

                LEFT JOIN propietarios pr ON u.id_usuario = pr.id_usuario
                LEFT JOIN centro_propietario prc ON pr.id_propietario = prc.id_propietario

                WHERE u.id_usuario = :id_usuario
            ");

            $stmt->execute([':id_usuario' => $id_usuario]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data || !$data['id_centro']) {
                throw new Exception("No se pudo obtener rol o centro del usuario.");
            }

            return $data;
        }


    }

      

?>