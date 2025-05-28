<?php

use Slim\App;
use App\Controllers\CentroController;
use Psr\Container\ContainerInterface;

return function (App $app, ContainerInterface $container) {

    $controller = $container->get(CentroController::class);

    $app->group('/centros', function ($group) use ($controller) {
        $group->post('', [$controller, 'create']);
        $group->get('', [$controller, 'getAll']);
        $group->put('/{id}', [$controller, 'update']);
        $group->delete('/{id}', [$controller, 'delete']);
    });
};

?>