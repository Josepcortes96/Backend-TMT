<?php

    require __DIR__ . '/../../vendor/autoload.php';
    use Slim\Factory\AppFactory;

    $container = require __DIR__ . '/../config/dependecies.php';
    AppFactory::setContainer($container);

    $app = AppFactory::create();

    $app-> run();

?>