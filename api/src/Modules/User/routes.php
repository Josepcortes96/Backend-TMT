<?php

    use Slim\App;
    use App\Controllers\UserController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $app->group('/api/v1', function ($group) {
            $group->get('/usuarios', [UserController::class, 'getAll']);
            $group->post('/usuarios', [UserController::class, 'create']);
            $group->get('/usuarios/{id}', [UserController::class, 'getOne']);
            $group->put('/usuarios/{id}', [UserController::class, 'update']);
            $group->delete('/usuarios/{id}', [UserController::class, 'delete']);
            $group->patch('/usuarios/{id}/inactivar', [UserController::class, 'deactivate']);
        });
    };
?>