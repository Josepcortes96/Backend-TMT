<?php

namespace App\Modules\Dato;

use App\Modules\Dato\DatoServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DatoController
{
    private DatoServiceInterface $datoService;

    public function __construct(DatoServiceInterface $datoService)
    {
        $this->datoService = $datoService;
    }

    public function crear(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $id = $this->datoService->crear($data);

            $response->getBody()->write(json_encode([
                'success' => true,
                'id_dato' => $id
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
            $id_dato = (int) $args['id'];
            $data = $request->getParsedBody();
            $success = $this->datoService->actualizar($id_dato, $data);

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

    public function eliminar(Request $request, Response $response, array $args): Response
    {
        try {
            $id_dato = (int) $args['id'];
            $this->datoService->eliminar($id_dato);

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Dato eliminado correctamente.'
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

    public function obtener(Request $request, Response $response, array $args): Response
    {
        try {
            $id_dato = (int) $args['id'];
            $dato = $this->datoService->obtenerPorId($id_dato);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $dato
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

    public function obtenerTodos(Request $request, Response $response): Response
    {
        try {
            $datos = $this->datoService->obtenerTodos();

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $datos
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

    public function obtenerPorControl(Request $request, Response $response, array $args): Response
    {
        try {
            $control = $args['control'];
            $dato = $this->datoService->obtenerPorControl($control);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $dato
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