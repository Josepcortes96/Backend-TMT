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
                $nombre = $data['nombre'] ?? '',
                $descripcion = $data['descripcion'] ?? '',
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


    public function asignarRol(Request $request, Response $response, array $args): Response
    {
        try {
            $id_dieta = (int) $args['id']; // ID de la dieta desde la URL
            $params = (array) $request->getParsedBody(); // Datos del body

            $id_usuario = (int) ($params['id_usuario'] ?? 0);
            $rol = $params['rol'] ?? '';

            if (!$id_usuario || !$rol) {
                throw new \InvalidArgumentException("Faltan datos obligatorios: id_usuario o rol");
            }

            // Asignar la dieta al usuario según su rol
            $resultado = $this->dietaService->asignarDietaSegunRol($id_dieta, $id_usuario, $rol);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $resultado
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

    public function obtenerConDato(Request $request, Response $response, array $args): Response
    {
        try {
            $id_dieta = (int) $args['id'];

            if (!$id_dieta) {
                throw new \InvalidArgumentException("ID de dieta no válido.");
            }

            $dieta = $this->dietaService->obtenerDietaConDato($id_dieta);

            if (!$dieta) {
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json')
                    ->write(json_encode([
                        'success' => false,
                        'message' => 'Dieta no encontrada.'
                    ]));
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $dieta
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


    public function obtenerPorUsuario(Request $request, Response $response, array $args): Response
    {
        try {
            $id_usuario = (int) $args['id_usuario'];

            if (!$id_usuario) {
                throw new \InvalidArgumentException("ID de usuario no válido.");
            }

            $dietas = $this->dietaService->obtenerDietasPorUsuario($id_usuario);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $dietas
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



    public function obtenerInforme(Request $request, Response $response, array $args): Response
    {
        try {
            $id_dieta = (int) $args['id'];

            $informe = $this->dietaService->obtenerInformeDieta($id_dieta);

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


      public function getUltimaDietaCreada(Request $request, Response $response, array $args): Response
    {
        try {
            $id_usuario = (int) $args['id_usuario'];

            if (!$id_usuario) {
                throw new \InvalidArgumentException("ID de usuario no válido.");
            }

            $dietas = $this->dietaService->getUltimaDietaCreada($id_usuario);

            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $dietas
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




}
?>

