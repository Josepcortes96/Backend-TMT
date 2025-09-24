<?php
use Slim\App;
use App\Controllers\PlantillaController;
use Psr\Container\ContainerInterface;
use App\Middlewares\JwtMiddleware;

return function (App $app, ContainerInterface $container) {
    $controller = $container->get(PlantillaController::class);
    $jwtMiddleware = $container->get(JwtMiddleware::class);

    $app->group('/api/v1/plantillas', function ($group) use ($controller) {
        $group->post('', [$controller, 'create']); // Crear plantilla
        $group->post('/{id}/asociar-comidas', [$controller, 'asociarComidas']); // Asociar comidas a plantilla
        $group->delete('/{id}', [$controller, 'delete']); // Eliminar plantilla
        $group->get('/centro', [$controller, 'getByCentro']); // Obtener todas las plantillas de un centro
        $group->get('/{id}', [$controller, 'getOne']); // Obtener una plantilla por ID (extra, si lo implementas)
        $group->get('', [$controller, 'getAll']); // Listar todas las plantillas (extra, si lo implementas)
    })->add($jwtMiddleware);
};
?>