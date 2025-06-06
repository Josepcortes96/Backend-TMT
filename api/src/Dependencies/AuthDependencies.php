<?php

    use App\Repositories\AuthRepository;
    use App\Repositories\Interfaces\AuthRepositoryInterface;
    use App\Services\AuthService;
    use App\Services\Interfaces\AuthServiceInterface;
    use App\Middlewares\JwtMiddleware;
   

    return [
        AuthRepositoryInterface::class => DI\autowire(AuthRepository::class),
        AuthServiceInterface::class => DI\autowire(AuthService::class),

        JwtMiddleware::class => function (\Psr\Container\ContainerInterface $c) {
            return new JwtMiddleware($c->get(AuthServiceInterface::class));
        },
    ];
?>