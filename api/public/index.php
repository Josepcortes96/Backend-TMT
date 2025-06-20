<?php

    declare(strict_types=1);
    require __DIR__ . '/../vendor/autoload.php';

    use Slim\Factory\AppFactory;

    $container = require __DIR__ . '/../src/Config/dependencies.php';
    AppFactory::setContainer($container);
    $app = AppFactory::create();

    // ✅ CORS middleware
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    });

   
    $app->options('/{routes:.+}', function ($request, $response) {
        return $response;
    });

    // Middlewares adicionales
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // Rutas
    (require __DIR__ . '/../src/Routes/userRoutes.php')($app, $container);
    (require __DIR__ . '/../src/Routes/centroRoutes.php')($app, $container);
    (require __DIR__ . '/../src/Routes/authRoutes.php')($app, $container);
    (require __DIR__ . '/../src/Routes/alimentoRoutes.php')($app, $container);
    (require __DIR__ . '/../src/Routes/comidaRoutes.php')($app, $container);
    (require __DIR__ . '/../src/Routes/equivalenciaRoutes.php')($app, $container);
    (require __DIR__ . '/../src/Routes/dietaRoutes.php')($app, $container);
    (require __DIR__ . '/../src/Routes/datoRoutes.php')($app, $container);

    $app->run();

?>