<?php

    declare(strict_types=1);
    require __DIR__ . '/../vendor/autoload.php';

    use Slim\Factory\AppFactory;
    

    $container = require __DIR__ . '/../src/Config/dependencies.php';
    AppFactory::setContainer($container);
    $app = AppFactory::create();

    //MIDDLEWARE
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // RUTAS
    (require __DIR__ . '/../src/Modules/User/routes.php')($app, $container);
    (require __DIR__ . '/../src/Modules/Equivalencia/routes.php')($app, $container);
    (require __DIR__ . '/../src/Modules/Dieta/routes.php')($app, $container);
    (require __DIR__ . '/../src/Modules/Dato/routes.php')($app, $container);
    (require __DIR__ . '/../src/Modules/Comida/routes.php')($app, $container);
    (require __DIR__ . '/../src/Modules/Centro/routes.php')($app, $container);
    (require __DIR__ . '/../src/Modules/Auth/routes.php')($app, $container);
    (require __DIR__ . '/../src/Modules/Alimento/routes.php')($app, $container);


    $app->run();

?>