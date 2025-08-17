<?php
use Slim\App;
use App\Controllers\DietaController;
use Psr\Container\ContainerInterface;
use App\Middlewares\JwtMiddleware;

return function (App $app, ContainerInterface $container) {
    $controller = $container->get(DietaController::class);
     $jwtMiddleware = $container->get(JwtMiddleware::class);

    $app->group('/api/v1/dietas', function ($group) use ($controller) {
        $group->post('', [$controller, 'crear']); // Crear dieta con macros
        $group->post('/asociar-comidas', [$controller, 'asociarComidas']); // Asociar comidas a dieta
        $group->post('/{id}/asignar-rol', [$controller, 'asignarRol']); // Asignar dieta por rol
        $group->put('/{id}', [$controller, 'actualizar']); // Actualizar macros de dieta
        $group->delete('/{id}', [$controller, 'eliminar']); // Eliminar dieta
        $group->get('', [$controller, 'listar']); // Listar todas las dietas
        $group->get('/{id}', [$controller, 'obtener']); // Obtener dieta por ID
        $group->get('/usuario/{id_usuario}', [$controller, 'obtenerPorUsuario']);
        $group->get('/{id}/dato', [$controller, 'obtenerConDato']);
        
    })->add($jwtMiddleware);;
};

?>