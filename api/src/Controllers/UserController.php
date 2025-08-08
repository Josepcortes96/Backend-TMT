<?php

    namespace App\Controllers;

    use App\Models\User;
    use App\Services\Interfaces\UserServiceInterface;
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class UserController {

        private UserServiceInterface $userService;

        public function __construct(UserServiceInterface $userService){
            $this-> userService = $userService;
        }

        public function create(Request $request, Response $response): Response {
            $data = $request->getParsedBody();
            $user = new User($data);
            $userId = $this->userService->createUser($user);
            $response->getBody()->write(json_encode(['id' => $userId]));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(201); 
;
        }

        public function getAll(Request $request, Response $response): Response {
            $users = $this->userService->getAll();
            $response->getBody()->write(json_encode($users));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function getOne(Request $request, Response $response, array $args): Response {
            $user = $this->userService->getOne((int) $args['id']);
            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function getByName(Request $request, Response $response, array $args): Response {
            $nombre = $args['nombre'] ?? '';
            $user = $this->userService->getUserName($nombre);
            if (empty($user)) {
                return $response->withStatus(404)->write(json_encode(['error' => 'Usuario no encontrado']));
            }
            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json');
        }

   public function update(Request $request, Response $response, array $args): Response {
    $data = $request->getParsedBody();
    $userId = (int) $args['id']; // ← ID del usuario que quieres modificar

    // ⚠️ Forzamos el rol correcto desde la base de datos del usuario a modificar (ID = $userId)
    $rol = $this->userService->getUserRol($userId);
    $data['rol'] = $rol;

    // Solo si no viene el centro, lo recuperamos del usuario a modificar
    if (!isset($data['centro_id'])) {
        $data['centro_id'] = $this->userService->getCentroId($userId, $rol);
    }

    error_log("📥 Datos corregidos: " . print_r($data, true));

    $user = new User($data);
    $this->userService->updateUser($userId, $user);

    $response->getBody()->write(json_encode(['status' => 'updated']));
    return $response->withHeader('Content-Type', 'application/json');
}




        public function delete(Request $request, Response $response, array $args): Response {
            $this->userService->deleteUser((int) $args['id']);
            $response->getBody()->write(json_encode(['status' => 'deleted']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function deactivate(Request $request, Response $response, array $args): Response {
            $this->userService->desactivarUser((int) $args['id']);
            $response->getBody()->write(json_encode(['status' => 'inactive']));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function getByCentro(Request $request, Response $response): Response {
            $user = $request->getAttribute('user');

            if (!$user || !isset($user['centro_id'])) {
                $response->getBody()->write(json_encode(['error' => 'Centro no identificado.']));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
            }

            $centroId = (int) $user['centro_id'];

            try {
                $usuarios = $this->userService->getUsersByCentro($centroId);
                $response->getBody()->write(json_encode($usuarios));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode([
                    'error' => 'Error al obtener los usuarios del centro.',
                    'details' => $e->getMessage()
                ]));
                return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
            }
        }


    }