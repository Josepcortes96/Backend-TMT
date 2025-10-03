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
            $group->get('/last/{id_usuario}', [$controller, 'obtenerUltimoControl']);
            $group->get('/usuario/{id_usuario}', [$controller, 'obtenerControles']);
            $group->get('/usuario/{id_usuario}/control/{nombre}', [$controller, 'obtenerPorControl']);
            $group->get('/usuario/{id_usuario}/dato/{id_dato}', [$controller, 'getInformeDato']);
            $group->get('/historico/{id_usuario}', [$controller, 'getInformeDatoHistorico']);
            $group->get('/detalle/{id}', [$controller, 'obtener']);
            $group->put('/{id}', [$controller, 'actualizar']);
            $group->delete('/{id}', [$controller, 'eliminar']);
        })->add($jwtMiddleware);

    };

?>
