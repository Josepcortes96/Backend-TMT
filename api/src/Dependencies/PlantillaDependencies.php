<?php

use App\Repositories\PlantillaRepository;
use App\Repositories\Interfaces\PlantillaRepositoryInterface;
use App\Services\PlantillaService;
use App\Services\Interfaces\PlantillaServiceInterface;


    return[
        PlantillaServiceInterface::class => DI\autowire(PlantillaService::class),
        PlantillaRepositoryInterface::class => DI\autowire(PlantillaRepository::class),
    ];

?>