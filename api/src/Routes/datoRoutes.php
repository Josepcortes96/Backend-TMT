<?php

    use Slim\App;
    use App\Controllers\DatoController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $controller = $container->get(DatoController::class);

        $app->group('/datos', function ($group) use ($controller) {
            $group->post('', [$controller, 'crear']);                            // Crear dato
            $group->get('', [$controller, 'obtenerTodos']);                      // Obtener todos
            $group->get('/{id}', [$controller, 'obtener']);                      // Obtener por ID
            $group->get('/control/{control}', [$controller, 'obtenerPorControl']); // Obtener por control
            $group->put('/{id}', [$controller, 'actualizar']);                   // Actualizar
            $group->delete('/{id}', [$controller, 'eliminar']);                  // Eliminar
        });
    };

?>