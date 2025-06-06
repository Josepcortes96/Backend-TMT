<?php
    use App\Controllers\EquivalenciaController;
    use Psr\Container\ContainerInterface;
    use Slim\App;

    return function (App $app, ContainerInterface $container) {
        $controller = $container->get(EquivalenciaController::class);

        $app->group('/api/v1/equivalencias', function ($group) use ($controller) {
            $group->get('/calcular', [$controller, 'calcular']);
        });
    };


?>