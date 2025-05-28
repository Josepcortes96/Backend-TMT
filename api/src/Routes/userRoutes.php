<?php

    use Slim\App;
    use App\Controllers\UserController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $app->group('/usuarios', function ($group) {
            $group->get('', [UserController::class, 'getAll']);
            $group->post('', [UserController::class, 'create']);
            $group->get('/{id}', [UserController::class, 'getOne']);
            $group->put('/{id}', [UserController::class, 'update']);
            $group->delete('/{id}', [UserController::class, 'delete']);
            $group->patch('/{id}/inactivar', [UserController::class, 'deactivate']);
        });
    };
?>