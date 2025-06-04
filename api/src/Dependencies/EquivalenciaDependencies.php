<?php

use App\Modules\Equivalencia\EquivalenciaRepository;
use App\Modules\Equivalencia\EquivalenciaRepositoryInterface;
use App\Modules\Equivalencia\EquivalenciaService;
use App\Modules\Equivalencia\EquivalenciaServiceInterface;

    return[
        EquivalenciaServiceInterface::class => DI\autowire(EquivalenciaService::class),
        EquivalenciaRepositoryInterface::class => DI\autowire(EquivalenciaRepository::class),
    ];
    ?>