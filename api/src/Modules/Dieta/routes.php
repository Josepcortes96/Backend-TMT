<?php
use Slim\App;
use App\Modules\Dieta\DietaController;
use Psr\Container\ContainerInterface;

return function (App $app, ContainerInterface $container) {
    $controller = $container->get(DietaController::class);

    $app->group('/api/v1', function ($group) use ($controller) {
        $group->post('/dietas', [$controller, 'crear']); // Crear dieta con macros
        $group->post('/dietas/asociar-comidas', [$controller, 'asociarComidas']); // Asociar comidas a dieta
        $group->put('/dietas/{id}', [$controller, 'actualizar']); // Actualizar macros de dieta
        $group->delete('/dietas/{id}', [$controller, 'eliminar']); // Eliminar dieta
        $group->get('/dietas', [$controller, 'listar']); // Listar todas las dietas
        $group->get('/dietas/{id}', [$controller, 'obtener']); // Obtener dieta por ID
    });
};

?>