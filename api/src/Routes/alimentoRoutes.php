<?php

use Slim\App;
use App\Controllers\AlimentoController;
use Psr\Container\ContainerInterface;

return function (App $app, ContainerInterface $container) {
    $controller = $container->get(AlimentoController::class);

    $app->group('/api/v1/alimentos', function ($group) use ($controller) {
        $group->post('', [$controller, 'create']);                  // Crear alimento
        $group->get('', [$controller, 'getAll']);                   // Obtener todos los alimentos
        $group->get('/id/{id}', [$controller, 'getById']);          // Obtener por ID
        $group->get('/nombre/{nombre}', [$controller, 'getByNombre']);  // Obtener por nombre
        $group->get('/familia/{familia}', [$controller, 'getByFamilia']); // Obtener por familia
        $group->post('/calcular', [$controller, 'calcular']);       // Calcular valores nutricionales
    });
};
