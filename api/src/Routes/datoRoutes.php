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

            $group->get('/ultimos/{id_usuario}', [$controller, 'obtenerUltimosControles']);
            $group->get('/usuario/{id_usuario}', [$controller, 'obtenerControles']);
            $group->get('/usuario/{id_usuario}/control/{nombre}', [$controller, 'obtenerPorControl']);
            
            $group->get('/detalle/{id}', [$controller, 'obtener']);
            $group->put('/{id}', [$controller, 'actualizar']);
            $group->delete('/{id}', [$controller, 'eliminar']);
        })->add($jwtMiddleware);


    };

?>
