<?php

    use Slim\App;
    use App\Controllers\UserController;

    return function (App $app) {
        $app->post('/users' , [UserController::class, 'create']);
    };
?>