<?php

    namespace App\Modules\Centro;

    use App\Modules\Centro\CentroServiceInterface;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class CentroController {

        private CentroServiceInterface $centroService;

        public function __construct(CentroServiceInterface $centroService) {
            $this->centroService = $centroService;
        }

        public function create(Request $request, Response $response): Response {
            $data = $request->getParsedBody();

            $success = $this->centroService->createCentro(
                $data['nombre'],
                $data['direccion'],
                $data['telefono'],
                $data['nombre_fiscal'],
                $data['NIF'],
                $data['ciudad'],
                $data['codigo_postal'],
                $data['pais'],
                $data['correo']
            );

            $response->getBody()->write(json_encode(['success' => $success]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function update(Request $request, Response $response, array $args): Response {
            $data = $request->getParsedBody();
            $id = (int) $args['id'];

            $this->centroService->updateCentro(
                $id,
                $data['nombre'],
                $data['direccion'],
                $data['telefono'],
                $data['nombre_fiscal'],
                $data['NIF'],
                $data['ciudad'],
                $data['codigo_postal'],
                $data['pais'],
                $data['correo']
            );

            $response->getBody()->write(json_encode(['success' => true]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function getAll(Request $request, Response $response): Response {
            $centros = $this->centroService->getCentro();
            $response->getBody()->write(json_encode($centros));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function delete(Request $request, Response $response, array $args): Response {
            $id = (int) $args['id'];
            $success = $this->centroService->deleteCentro($id);

            $response->getBody()->write(json_encode(['success' => $success]));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>