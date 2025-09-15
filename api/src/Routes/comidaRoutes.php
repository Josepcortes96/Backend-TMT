<?php
    use Slim\App;
    use App\Controllers\ComidaController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
    $controller = $container->get(ComidaController::class);

    $app->group('/api/v1/comidas', function ($group) use ($controller) {
        $group->post('', [$controller, 'crear']); // Crear comidas con alimentos
        $group->post('/agregar-alimento', [$controller, 'agregar']);
        $group->delete('', [$controller, 'eliminarVarias']); 

    });
};

  
?>