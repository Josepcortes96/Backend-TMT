<?php

    use Slim\App;
    use App\Modules\Auth\AuthController;
    use Psr\Container\ContainerInterface;

    return function (App $app, ContainerInterface $container) {
        $controller = $container->get(AuthController::class);

        $app->group('/api/v1', function ($group) use ($controller) {
            $group->post('/auth/login', [$controller, 'login']);
            $group->post('/auth/check', [$controller, 'check']);
        });
    };
?>