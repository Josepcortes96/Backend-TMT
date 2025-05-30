<?php
    use App\Controllers\ComidaController;

    $router->post('/comidas', [ComidaController::class, 'crear']);
    $router->post('/comidas/agregar-alimento', [ComidaController::class, 'agregar']);
?>