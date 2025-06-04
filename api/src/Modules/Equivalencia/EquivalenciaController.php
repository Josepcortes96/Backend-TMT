<?php

namespace App\Modules\Equivalencia;

use App\Modules\Equivalencia\EquivalenciaServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controlador para operaciones relacionadas con equivalencias alimenticias.
 */
class EquivalenciaController
{
    public function __construct(private EquivalenciaServiceInterface $service) {}

    /**
     * Calcula la equivalencia entre dos alimentos con base en su categoría nutricional.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function calcular(Request $request, Response $response, array $args): Response
    {
        try {
            $params = $request->getQueryParams();

            $idPrincipal   = isset($params['id_alimento']) ? (int)$params['id_alimento'] : null;
            $idEquivalente = isset($params['id_equivalente']) ? (int)$params['id_equivalente'] : null;
            $categoria     = $params['categoria'] ?? null;
            $cantidad      = isset($params['cantidad']) ? (float)$params['cantidad'] : null;

            if (!$idPrincipal || !$idEquivalente || !$categoria || !$cantidad) {
                $response->getBody()->write(json_encode(['error' => 'Faltan parámetros requeridos']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $result = $this->service->calcularEquivalencia($idPrincipal, $idEquivalente, $categoria, $cantidad);

            $response->getBody()->write(json_encode(['success' => true, 'data' => $result]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Error inesperado',
                'detalle' => $e->getMessage()
            ]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
?>