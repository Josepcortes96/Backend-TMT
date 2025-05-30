<?php

use App\Repositories\EquivalenciaRepository;
use App\Repositories\Interfaces\EquivalenciaRepositoryInterface;
use App\Services\EquivalenciaService;
use App\Services\Interfaces\EquivalenciaServiceInterface;

    return[
        EquivalenciaServiceInterface::class => DI\autowire(EquivalenciaService::class),
        EquivalenciaRepositoryInterface::class => DI\autowire(EquivalenciaRepository::class),
    ];
    ?>