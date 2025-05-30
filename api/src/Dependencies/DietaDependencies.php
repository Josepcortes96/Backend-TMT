<?php

use App\Repositories\DietaRepository;
use App\Repositories\Interfaces\DietaRepositoryInterface;
use App\Services\DietaService;
use App\Services\Interfaces\DietaServiceInterface;


    return[
        DietaServiceInterface::class => DI\autowire(DietaService::class),
        DietaRepositoryInterface::class => DI\autowire(DietaRepository::class),
    ];

?>