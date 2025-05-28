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
            return $response->withHeader('Content-Type', 'application/json');
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

        public function update(Request $request, Response $response, array $args): Response {
            $data = $request->getParsedBody();

            if (!isset($data['centro_id'])) {
                $userId = (int) $args['id'];
                $rol = $this->userService->getUserRol($userId);
                $data['centro_id'] = $this->userService->getCentroId($userId, $rol);
            }

            $user = new User($data);
            $this->userService->updateUser((int) $args['id'], $user);

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

    }