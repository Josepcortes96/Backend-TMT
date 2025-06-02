<?php

use App\Auth\AuthRepository;
use App\Auth\AuthRepositoryInterface;
use App\Auth\AuthService;
use App\Auth\AuthServiceInterface;



return [
    AuthRepositoryInterface::class => DI\autowire(AuthRepository::class),
    AuthServiceInterface::class => DI\autowire(AuthService::class),
];

?>