<?php

namespace App\Modules\Dieta;

use App\Modules\Dieta\DietaServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DietaController
{
    private DietaServiceInterface $dietaService;

    public function __construct(DietaServiceInterface $dietaService)
    {
        $this->dietaService = $dietaService;
    }

    public function crear(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $idDieta = $this->dietaService->crearDietaConMacros($data);

            $response->getBody()->write(json_encode([
                'success' => true,
                'id_dieta' => $idDieta
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

    public function asociarComidas(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            if (!isset($data['id_dieta']) || !isset($data['comidas']) || !is_array($data['comidas'])) {
                throw new \Exception("Se requiere id_dieta y un array de comidas.");
            }

            $this->dietaService->asociarComidas((int) $data['id_dieta'], $data['comidas']);

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Comidas asociadas correctamente.'
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
            $id_dieta = (int) $args['id'];
            $data = $request->getParsedBody();

            $result = $this->dietaService->actualizarMacros(
                $id_dieta,
                $data['proteinas_dieta'] ?? 0,
                $data['grasas_dieta'] ?? 0,
                $data['carbohidratos_dieta'] ?? 0
            );

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
            $id_dieta = (int) $args['id'];
            $this->dietaService->eliminarDieta($id_dieta);

            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Dieta eliminada correctamente.'
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

    public function listar(Request $request, Response $response): Response
    {
        try {
            $dietas = $this->dietaService->obtenerTodas();

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

    public function obtener(Request $request, Response $response, array $args): Response
    {
        try {
            $id_dieta = (int) $args['id'];
            $dieta = $this->dietaService->obtenerPorId($id_dieta);

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
}
?>