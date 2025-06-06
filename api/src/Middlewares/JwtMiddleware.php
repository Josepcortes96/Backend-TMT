<?php

namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class JwtMiddleware implements MiddlewareInterface {
    private string $secret;

    public function __construct() {
        $this->secret = $_ENV['JWT_SECRET'] ?? throw new \Exception('JWT_SECRET no definido');
    }

    public function process(Request $request, Handler $handler): ResponseInterface {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->unauthorized('Token no proporcionado');
        }

        $token = $matches[1];

        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));

            $request = $request->withAttribute('user', [
                'id_usuario' => $decoded->id_usuario ?? null,
                'rol' => $decoded->rol ?? null,
                'centro_id' => $decoded->centro_id ?? null
            ]);

            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->unauthorized('Token inválido o expirado');
        }
    }

    private function unauthorized(string $message): ResponseInterface {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
    }
}
?>