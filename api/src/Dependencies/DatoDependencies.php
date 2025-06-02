<?php

use App\Repositories\DatoRepository;
use App\Repositories\Interfaces\DatoRepositoryInterface;
use App\Services\DatoService;
use App\Services\Interfaces\DatoServiceInterface;

return[
    DatoServiceInterface::class => DI\autowire(DatoService::class),
    DatoRepositoryInterface::class => DI\autowire(DatoRepository::class),
]
?>