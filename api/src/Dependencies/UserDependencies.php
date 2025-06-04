<?php



use App\Modules\User\UserRepositoryInterface;
use App\Modules\User\UserRepository;
use App\Modules\User\UserServiceInterface;
use App\Modules\User\UserService;


return [
    UserRepositoryInterface::class => DI\autowire(UserRepository::class),
    UserServiceInterface::class => DI\autowire(UserService::class),
];

?>