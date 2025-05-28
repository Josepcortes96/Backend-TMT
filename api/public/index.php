<?php

    declare(strict_types=1);
    require __DIR__ . '/../vendor/autoload.php';

    use Slim\Factory\AppFactory;
    use Slim\Psr7\Response;
    use Slim\Exception\HttpNotFoundException;

    $container = require __DIR__ . '/../src/Config/dependencies.php';
    AppFactory::setContainer($container);
    $app = AppFactory::create();

    //MIDDLEWARE
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // RUTAS
    (require __DIR__ . '/../src/Routes/userRoutes.php')($app);


    $app->run();

?>