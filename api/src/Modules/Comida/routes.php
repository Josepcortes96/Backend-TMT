<?php
    use Slim\App;
    use App\Modules\Comida\ComidaController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
    $controller = $container->get(ComidaController::class);

    $app->group('/api/v1', function ($group) use ($controller) {
        $group->post('/comidas', [$controller, 'crear']); 
        $group->post('/comidas/agregar-alimento', [$controller, 'agregar']);
    });
};

  
?>