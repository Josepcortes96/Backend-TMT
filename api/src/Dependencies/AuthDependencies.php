<?php

use App\Repositories\AuthRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Services\AuthService;
use App\Services\Interfaces\AuthServiceInterface;



return [
    AuthRepositoryInterface::class => DI\autowire(AuthRepository::class),
    AuthServiceInterface::class => DI\autowire(AuthService::class),
];

?>