<?php
use Slim\App;
use App\Controllers\DietaController;
use Psr\Container\ContainerInterface;
use App\Middlewares\JwtMiddleware;

return function (App $app, ContainerInterface $container) {
    $controller = $container->get(DietaController::class);
    $jwtMiddleware = $container->get(JwtMiddleware::class);

    $app->group('/api/v1/dietas', function ($group) use ($controller) {
        //  RUTAS ESTÁTICAS Y MÁS ESPECÍFICAS PRIMERO
        $group->post('', [$controller, 'crear']); 
        $group->get('', [$controller, 'listar']);
        $group->post('/asociar-comidas', [$controller, 'asociarComidas']);
        
        //  Rutas con /usuario y /last ANTES de /{id}
        $group->get('/last/{id_usuario}', [$controller, 'ultimaDietaCreada']);
        $group->get('/usuario/{id_usuario}', [$controller, 'obtenerPorUsuario']);
        
        //  Rutas con /informe ANTES de /{id}
        $group->get('/informe/{id}', [$controller, 'obtenerInforme']);
        
        //  RUTAS GENÉRICAS CON /{id} AL FINAL
        $group->post('/{id}/asignar-rol', [$controller, 'asignarRol']);
        $group->get('/{id}/dato', [$controller, 'obtenerConDato']);
        $group->put('/{id}', [$controller, 'actualizar']);
        $group->delete('/{id}', [$controller, 'eliminar']);
        $group->get('/{id}', [$controller, 'obtener']);
       
    })->add($jwtMiddleware);
};
?>