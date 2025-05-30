<?php

use App\Repositories\AlimentoRepository;
use App\Repositories\ComidaRepository;
use App\Repositories\Interfaces\AlimentoRepositoryInterface;
use App\Repositories\Interfaces\ComidaRepositoryInterface;
use App\Services\ComidaService;
use App\Services\Interfaces\ComidaServiceInterface;



return [
    ComidaServiceInterface::class => DI\autowire(ComidaService::class),
    ComidaRepositoryInterface::class => DI\autowire(ComidaRepository::class),
    AlimentoRepositoryInterface::class => DI\autowire(AlimentoRepository::class),

];

?>