<?php

use App\Modules\Auth\AuthRepository;
use App\Modules\Auth\AuthRepositoryInterface;
use App\Modules\Auth\AuthService;
use App\Modules\Auth\AuthServiceInterface;



return [
    AuthRepositoryInterface::class => DI\autowire(AuthRepository::class),
    AuthServiceInterface::class => DI\autowire(AuthService::class),
];

?>