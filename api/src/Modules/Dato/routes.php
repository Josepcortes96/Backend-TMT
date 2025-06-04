<?php

    use Slim\App;
    use App\Modules\Dato\DatoController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $controller = $container->get(DatoController::class);

        $app->group('/api/v1', function ($group) use ($controller) {
            $group->post('/datos', [$controller, 'crear']);                            // Crear dato
            $group->get('/datos', [$controller, 'obtenerTodos']);                      // Obtener todos
            $group->get('/datos/{id}', [$controller, 'obtener']);                      // Obtener por ID
            $group->get('/datos/control/{control}', [$controller, 'obtenerPorControl']); // Obtener por control
            $group->put('/datos/{id}', [$controller, 'actualizar']);                   // Actualizar
            $group->delete('/datos/{id}', [$controller, 'eliminar']);                  // Eliminar
        });
    };

?>