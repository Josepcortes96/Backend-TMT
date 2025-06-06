<?php
    use Slim\App;
    use App\Controllers\UserController;
    use App\Middlewares\JwtMiddleware;
    use App\Services\AuthService;
    use App\Repositories\AuthRepository;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $pdo = $container->get(PDO::class); // <-- ahora funciona correctamente

        $authRepository = new AuthRepository($pdo);
        $authService = new AuthService($authRepository);
        $jwtMiddleware = new JwtMiddleware($authService);

        $app->group('/api/v1/usuarios', function ($group) {
            $group->get('', [UserController::class, 'getAll']);
            $group->get('/centro', [UserController::class, 'getByCentro']);
            $group->post('', [UserController::class, 'create']);
            $group->get('/{id}', [UserController::class, 'getOne']);
            $group->put('/{id}', [UserController::class, 'update']);
            $group->delete('/{id}', [UserController::class, 'delete']);
            $group->patch('/{id}/inactivar', [UserController::class, 'deactivate']);
        })->add($jwtMiddleware);
    };
?>