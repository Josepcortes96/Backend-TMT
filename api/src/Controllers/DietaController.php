<?php

namespace App\Controllers;

use App\Services\Interfaces\DietaServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DietaController
{
    private DietaServiceInterface $dietaService;

    public function __construct(DietaServiceInterface $dietaService)
    {
        $this->dietaService = $dietaService;
    }

    public function obtenerTodas(Request $request, Response $response): Response
    {
        try {
            $dietas = $this->dietaService->getAllDietas();

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $dietas
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

    public function obtenerPorId(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $dieta = $this->dietaService->getDietaById($id);

            if (!$dieta) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Dieta no encontrada'
                ]));
                return $response->withStatus(404);
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $dieta
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

    public function crear(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $result = $this->dietaService->createDieta($data);

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

    public function actualizar(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $macros = $request->getParsedBody();
            $result = $this->dietaService->actualizarDieta($id, $macros);

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

    public function eliminar(Request $request, Response $response, array $args): Response
    {
        try {
            $id = (int) $args['id'];
            $success = $this->dietaService->deleteDieta($id);

            $response->getBody()->write(json_encode([
                'success' => $success
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

    public function asociarComidas(Request $request, Response $response, array $args): Response
    {
        try {
            $id_dieta = (int) $args['id'];
            $body = $request->getParsedBody();

            if (!isset($body['comidas']) || !is_array($body['comidas'])) {
                throw new \Exception("Se espera un array de comidas");
            }

            $result = $this->dietaService->asociarComidas($id_dieta, $body['comidas']);

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