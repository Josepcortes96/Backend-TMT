<?php

use Slim\App;
use App\Modules\Alimento\AlimentoController;
use Psr\Container\ContainerInterface;

return function (App $app, ContainerInterface $container) {
    $controller = $container->get(AlimentoController::class);

    $app->group('/api/v1', function ($group) use ($controller) {
        $group->post('/alimentos', [$controller, 'create']);                  
        $group->get('/alimentos', [$controller, 'getAll']);                   
        $group->get('/alimentos/id/{id}', [$controller, 'getById']);          
        $group->get('/alimentos/nombre/{nombre}', [$controller, 'getByNombre']);  
        $group->get('/alimentos/familia/{familia}', [$controller, 'getByFamilia']); 
        $group->post('/alimentos/calcular', [$controller, 'calcular']);      
    });
};
?>
