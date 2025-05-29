<?php

    use Slim\App;
    use App\Controllers\AuthController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $controller = $container->get(AuthController::class);

        $app->group('/auth', function ($group) use ($controller) {
            $group->post('/login', [$controller, 'login']);
            $group->post('/check', [$controller, 'check']);
        });
    };
?>