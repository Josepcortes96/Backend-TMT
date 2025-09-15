<?php

    namespace App\Controllers;

    use App\Services\Interfaces\AuthServiceInterface;
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
                $token = $this->authService->login($data['correo'], $data['password']);
                $response->getBody()->write(json_encode(['token' => $token]));
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
                return $response->withStatus(401);
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

       public function check(Request $request, Response $response): Response {
            $authHeader = $request->getHeaderLine('Authorization');

            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            } else {
                $response->getBody()->write(json_encode([
                    'message' => 'NO_TOKEN'
                ]));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }

            $valido = $this->authService->validarToken($token);

            $response->getBody()->write(json_encode([
                'message' => $valido ? 'OK' : 'NO'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($valido ? 200 : 401);
        }

    }
?>