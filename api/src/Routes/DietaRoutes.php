<?php

    use App\Controllers\DietaController;
    use Psr\Container\ContainerInterface;
    use Slim\App;

    return function (App $app, ContainerInterface $container) {
        $controller = $container->get(DietaController::class);

        $app->group('/dietas', function ($group) use ($controller) {

            $group->get('', [$controller, 'obtenerTodas']);
            $group->get('/{id}', [$controller, 'obtenerPorId']);
            $group->post('', [$controller, 'crear']);
            $group->put('/{id}', [$controller, 'actualizar']);
            $group->delete('/{id}', [$controller, 'eliminar']);
            $group->post('/{id}/comidas', [$controller, 'asociarComidas']);
            
        });
    };
?>