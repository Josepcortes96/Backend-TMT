<?php

    namespace App\Modules\Auth;

    use App\Modules\Auth\AuthServiceInterface;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class AuthController {
        private AuthServiceInterface $authService;

        public function __construct(AuthServiceInterface $authService) {
            $this->authService = $authService;
        }

        public function login(Request $request, Response $response): Response {
            $data = $request->getParsedBody();

            try {
                $token = $this->authService->login($data['username'], $data['password']);
                $response->getBody()->write(json_encode(['token' => $token]));
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
                return $response->withStatus(401);
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function check(Request $request, Response $response): Response {
            $data = $request->getParsedBody();
            $token = $data['token'] ?? '';

            $valido = $this->authService->validarToken($token);

            $response->getBody()->write(json_encode([
                'valido' => $valido ? 'OK' : 'NO'
            ]));

            return $response->withHeader('Content-Type', 'application/json');
        }
    }
?>