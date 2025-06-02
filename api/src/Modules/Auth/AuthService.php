<?php

    namespace App\Auth;

    use App\Auth\AuthServiceInterface;
    use App\Auth\AuthRepositoryInterface;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class AuthService implements AuthServiceInterface {
        private AuthRepositoryInterface $authRepository;
        private string $secretKey;

        public function __construct(AuthRepositoryInterface $authRepository) {
            $this->authRepository = $authRepository;
            $this->secretKey = $_ENV['JWT_SECRET'] ?? throw new \Exception("JWT_SECRET no está definido en .env");
        }

        public function login(string $username, string $password): string {
            $userId = $this->authRepository->verificarCredenciales($username, $password);

            $payload = [
                'iss' => 'localhost',
                'iat' => time(),
                'exp' => time() + 7200,
                'id_usuario' => $userId
            ];

            return JWT::encode($payload, $this->secretKey, 'HS256');
        }

        public function validarToken(string $token): bool {
            try {
                JWT::decode($token, new Key($this->secretKey, 'HS256'));
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }

?>