<?php

use Slim\App;
use App\Modules\Centro\CentroController;
use Psr\Container\ContainerInterface;

return function (App $app, ContainerInterface $container) {

    $controller = $container->get(CentroController::class);

    $app->group('/api/v1', function ($group) use ($controller) {
        $group->post('/centros', [$controller, 'create']);
        $group->get('/centros', [$controller, 'getAll']);
        $group->put('/centros/{id}', [$controller, 'update']);
        $group->delete('/centros/{id}', [$controller, 'delete']);
    });
};

?>