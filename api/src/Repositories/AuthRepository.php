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

        /**
         * Verifica las credenciales de un usuario comparando username y contraseña.
         *
         * @param string $username Nombre de usuario proporcionado en el login.
         * @param string $password Contraseña en texto plano que se validará contra el hash almacenado.
         *
         * @return int Devuelve el ID único del usuario si las credenciales son correctas.
         *
         * @throws Exception Si el usuario no existe o la contraseña no coincide.
         */

        public function verificarCredenciales(string $username, string $password): int {
            $stmt = $this->pdo->prepare("SELECT id_usuario, password FROM usuarios WHERE username = :username");
            $stmt->execute([':username' => $username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception("Credenciales inválidas.");
            }

            return (int) $user['id_usuario'];
        }
        
        /**
         * Registra un evento de login en la base de datos utilizando un procedimiento almacenado.
         *
         * @param int    $id_usuario Identificador único del usuario que inicia sesión.
         * @param string $ip         Dirección IP desde la que se realizó el login.
         * @param string $userAgent  Información del navegador/cliente desde el que se accede.
         * @param string $jwtHash    Hash del token JWT generado para la sesión.
         *
         * @return void No devuelve valor; solo registra la información.
         *
         * @throws PDOException Si ocurre un error al ejecutar el procedimiento almacenado.
         */

        public function registrarLogin(int $id_usuario, string $ip, string $userAgent, string $jwtHash): void {
            $stmt = $this->pdo->prepare("CALL sp_log_user_login(:id_usuario, :ip, :userAgent, :jwtHash)");
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':ip' => $ip,
                ':userAgent' => $userAgent,
                ':jwtHash' => $jwtHash
            ]);
        }

        /**
         * Obtiene el rol y el centro asociado a un usuario según su perfil (Cliente, Preparador, Propietario, Administrador).
         *
         * - Cliente → centro de la tabla centro_cliente
         * - Preparador → centro de la tabla centro_preparador
         * - Propietario → centro de la tabla centro_propietario
         * - Administrador → no requiere centro (devuelve NULL en id_centro)
         *
         * @param int $id_usuario Identificador único del usuario.
         *
         * @return array {
         *     @type string $nombre    Nombre del usuario.
         *     @type string $rol       Rol del usuario (Cliente, Preparador, Propietario, Administrador).
         *     @type int|null $id_centro ID del centro asociado o NULL si no aplica.
         * }
         *
         * @throws Exception Si no se encuentra información del usuario o no tiene centro asignado
         *                   (excepto en el caso de Administrador).
         */

        public function obtenerRolYCentro(int $id_usuario): array {
            $stmt = $this->pdo->prepare("
               SELECT 
                u.nombre,
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

          if (!$data || (!$data['id_centro'] && $data['rol'] !== 'Administrador')) {
                throw new Exception("No se pudo obtener rol o centro del usuario.");
            }

            return $data;
        }


    }

      

?>