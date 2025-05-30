<?php

namespace App\Controllers;

use App\Services\Interfaces\ComidaServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ComidaController
{
    private ComidaServiceInterface $comidaService;

    public function __construct(ComidaServiceInterface $comidaService)
    {
        $this->comidaService = $comidaService;
    }

    public function crear(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $result = $this->comidaService->crearComidasConAlimentos($data);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $result
            ]));
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function agregar(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $result = $this->comidaService->agregarAlimentosAComida($data);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $result
            ]));
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(500);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>