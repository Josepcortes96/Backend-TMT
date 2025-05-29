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
    }
?>