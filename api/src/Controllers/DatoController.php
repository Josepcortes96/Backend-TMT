<?php

namespace App\Controllers;

use App\Services\Interfaces\DatoServiceInterface;
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
            $data = json_decode($request->getBody()->getContents(), true);

            if (!is_array($data)) {
                throw new \InvalidArgumentException('El cuerpo de la solicitud no es un JSON válido.');
            }

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
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
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
        $idUsuario = (int) $args['id_usuario'];
        $nombre = $args['nombre'];

        $dato = $this->datoService->getDatoByNombre($nombre, $idUsuario);

        if (!$dato) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Dato no encontrado'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $dato
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

public function obtenerUltimosControles(Request $request, Response $response, array $args): Response
{
    try {
        $idUsuario = (int) $args['id_usuario'];
        $controles = $this->datoService->getUltimosControles($idUsuario);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $controles
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




public function obtenerUltimoControl(Request $request, Response $response, array $args): Response
{
    try {
        $idUsuario = (int) $args['id_usuario'];
        $controles = $this->datoService->getUltimoControlPorId($idUsuario);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $controles
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


public function obtenerControles(Request $request, Response $response, array $args): Response
{
    try {
        $idUsuario = (int) $args['id_usuario'];
        $controles = $this->datoService->getTodosControles($idUsuario);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $controles
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

public function getInformeDato(Request $request, Response $response, array $args): Response
{
    try {
        $idUsuario = (int) $args['id_usuario'];
        $idDato = (int) $args['id_dato'];
        $informe = $this->datosService->getInformeDato($idUsuario, $idDato);

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

public function getInformeDatoHistorico(Request $request, Response $response, array $args): Response
{
    try {
        $idUsuario = (int) $args['id_usuario'];
        $historico = $this->datosService->getInformeDatoHistorico($idUsuario);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $historico
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