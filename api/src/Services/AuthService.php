<?php

    namespace App\Services;

    use App\Services\Interfaces\AuthServiceInterface;
    use App\Repositories\Interfaces\AuthRepositoryInterface;
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
            $this->validarFormatoPassword($password); 

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

        private function validarFormatoPassword(string $password): void {
            $regex = '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/';

            if (!preg_match($regex, $password)) {
                throw new \Exception("La contraseña debe contener al menos una mayúscula, un número y un signo.");
            }
        }

    }

?>