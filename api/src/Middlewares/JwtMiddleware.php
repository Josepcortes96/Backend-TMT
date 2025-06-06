<?php

namespace App\Middlewares;

use App\Services\Interfaces\AuthServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class JwtMiddleware implements MiddlewareInterface {
    private AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService) {
        $this->authService = $authService;
    }

    public function process(Request $request, Handler $handler): ResponseInterface {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->unauthorized('Token no proporcionado');
        }

        $token = $matches[1];

        if (!$this->authService->validarToken($token)) {
            return $this->unauthorized('Token inválido o expirado');
        }

        return $handler->handle($request);
    }

    private function unauthorized(string $message): ResponseInterface {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }
}
?>