<?php

use App\Modules\Dieta\DietaRepository;
use App\Modules\Dieta\DietaRepositoryInterface;
use App\Modules\Dieta\DietaService;
use App\Modules\Dieta\DietaServiceInterface;


    return[
        DietaServiceInterface::class => DI\autowire(DietaService::class),
        DietaRepositoryInterface::class => DI\autowire(DietaRepository::class),
    ];

?>