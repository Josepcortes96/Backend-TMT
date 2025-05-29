<?php



use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\UserService;


return [
    UserRepositoryInterface::class => DI\autowire(UserRepository::class),
    UserServiceInterface::class => DI\autowire(UserService::class),
];

?>