<?php

namespace App\Controllers;

use App\Services\Interfaces\AlimentoServiceInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlimentoController
{
    private AlimentoServiceInterface $alimentoService;

    public function __construct(AlimentoServiceInterface $alimentoService)
    {
        $this->alimentoService = $alimentoService;
    }

  public function create(Request $request, Response $response): Response
{
    $data = json_decode($request->getBody()->getContents(), true);
    $results = [];

    if (isset($data['nombre'])) {
        $data = [$data];
    }

    foreach ($data as $alimento) {
        $success = $this->alimentoService->createAlimento(
            $alimento['nombre'],
            (float)$alimento['calorias'],
            (float)$alimento['proteinas'],
            (float)$alimento['carbohidratos'],
            (float)$alimento['grasas'],
            $alimento['familia'],
            (float)$alimento['agua'],
            (float)$alimento['fibra'],
            $alimento['categoria']
        );

        $results[] = [
            'nombre'  => $alimento['nombre'],
            'success' => $success,
            'message' => $success
                ? 'Alimento creado/actualizado correctamente'
                : 'Error al crear el alimento'
        ];
    }

    $response->getBody()->write(json_encode($results));
    $raw = $request->getBody()->getContents();
        error_log("RAW BODY: " . $raw);
        $data = json_decode($raw, true);

    return $response->withHeader('Content-Type', 'application/json');
}



    public function getAll(Request $request, Response $response): Response
    {
        $alimentos = $this->alimentoService->getAlimentos();

        $response->getBody()->write(json_encode($alimentos));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getById(Request $request, Response $response, array $args): Response
    {
        $alimento = $this->alimentoService->getAlimentoPorId((int)$args['id']);

        $response->getBody()->write(json_encode($alimento ?? ['error' => 'Alimento no encontrado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getByNombre(Request $request, Response $response, array $args): Response
    {
        $alimento = $this->alimentoService->getAlimentoPorName($args['nombre']);

        $response->getBody()->write(json_encode($alimento ?? ['error' => 'Alimento no encontrado']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getByFamilia(Request $request, Response $response, array $args): Response
    {
        $alimentos = $this->alimentoService->getAlimentosFamilia($args['categoria']);

        $response->getBody()->write(json_encode($alimentos ?? ['error' => 'No se encontraron alimentos para esa familia']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function calcular(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $alimento = $this->alimentoService->getAlimentoPorId((int)$data['id_alimento']);
        $cantidad = (float)$data['cantidad'];

        if (!$alimento) {
            $response->getBody()->write(json_encode(['error' => 'Alimento no encontrado']));
        } else {
            $valores = $this->alimentoService->calcularValoresNutricionales($alimento, $cantidad);
            $response->getBody()->write(json_encode($valores));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
