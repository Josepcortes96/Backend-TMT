<?php

namespace App\Controllers;

use App\Services\Interfaces\PlantillaServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Exception;

class PlantillaController
{
    private PlantillaServiceInterface $plantillaService;

    public function __construct(PlantillaServiceInterface $plantillaService)
    {
        $this->plantillaService = $plantillaService;
    }

    /**
     * Crea una nueva plantilla
     */
    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        try {
            $id = $this->plantillaService->createPlantilla(
                $data['nombre'] ?? null,
                (int)$data['id_usuario'],
                (int)$data['id_centro'],
                $data['fecha_creacion'] ?? null
            );

            $response->getBody()->write(json_encode(['id' => $id]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    /**
     * Asocia comidas a una plantilla
     */
    public function asociarComidas(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $idPlantilla = (int)$args['id'];

        try {
            $this->plantillaService->asociarComidas($idPlantilla, $data['comidas'] ?? []);
            $response->getBody()->write(json_encode(['status' => 'comidas asociadas']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    /**
     * Elimina una plantilla por ID
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];

        try {
            $this->plantillaService->eliminarPlantilla($id);
            $response->getBody()->write(json_encode(['status' => 'deleted']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }
    }

    /**
     * Obtiene todas las plantillas de un centro
     */
    public function getByCentro(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');

        if (!$user || !isset($user['centro_id'])) {
            $response->getBody()->write(json_encode(['error' => 'Centro no identificado.']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        $centroId = (int)$user['centro_id'];

        try {
            $plantillas = $this->plantillaService->obtenerPlantillasPorCentro($centroId);
            $response->getBody()->write(json_encode($plantillas));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Error al obtener las plantillas del centro.',
                'details' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function obtenerInforme(Request $request, Response $response, array $args): Response
    {
        try {
            $id_plantilla = (int) $args['id'];

            $informe = $this->plantillaService->obtenerInformePlantilla($id_plantilla);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $informe
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function actualizar(Request $request, Response $response, array $args): Response
{
    try {

        $id_plantilla = (int) $args['id'];
        $data = $request->getParsedBody();

        // Llamamos al servicio
        $result = $this->plantillaService->actualizarPlantilla(
            $id_plantilla,
            $data['nombre'] ?? ''
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

}
