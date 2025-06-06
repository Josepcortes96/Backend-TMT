<?php

    use Slim\App;
    use App\Controllers\DatoController;
    use App\Middlewares\JwtMiddleware;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $controller = $container->get(DatoController::class);
        $jwtMiddleware = $container->get(JwtMiddleware::class);

        $app->group('/api/v1/datos', function ($group) use ($controller) {
            $group->post('', [$controller, 'crear']);
            $group->get('', [$controller, 'obtenerTodos']);
            $group->get('/{id}', [$controller, 'obtener']);
            $group->get('/control/{control}', [$controller, 'obtenerPorControl']);
            $group->put('/{id}', [$controller, 'actualizar']);
            $group->delete('/{id}', [$controller, 'eliminar']);
        })->add($jwtMiddleware);
    };

?>