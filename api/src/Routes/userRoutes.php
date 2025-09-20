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
            $group->get('/clientes', [UserController::class, 'getClientes']);
            $group->get('/preparadores', [UserController::class, 'getPreparadores']);
            $group->get('/propietarios', [UserController::class, 'getPropietarios']);
            $group->get('/cumpleaños', [UserController::class, 'getCumpleañosUsers']);
            $group->get('/activos', [UserController::class, 'getActivos']);
            $group->get('/centro', [UserController::class, 'getByCentro']);
            $group->get('/centro/activos', [UserController::class, 'getActivosCentro']);
            $group->get('/centro/ultimos', [UserController::class, 'getUltimosClientesCentro']);
            $group->get('/centro/cumpleaños', [UserController::class, 'getCumpleañosByCentro']);
            $group->get('/ultimos', [UserController::class, 'getUltimosClientes']);
            $group->post('', [UserController::class, 'create']);
            $group->get('/{id}', [UserController::class, 'getOne']);
            $group->get('/nombre/{nombre}', [UserController::class, 'getByName']);
            $group->put('/{id}', [UserController::class, 'update']);
            $group->delete('/{id}', [UserController::class, 'delete']);
            $group->patch('/{id}/inactivar', [UserController::class, 'deactivate']);
        })->add($jwtMiddleware); 
    };
?>