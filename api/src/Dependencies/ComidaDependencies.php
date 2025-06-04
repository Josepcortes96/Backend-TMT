<?php

use App\Modules\Alimento\AlimentoRepository;
use App\Modules\Comida\ComidaRepository;
use App\Modules\Alimento\AlimentoRepositoryInterface;
use App\Modules\Comida\ComidaRepositoryInterface;
use App\Modules\Comida\ComidaService;
use App\Modules\Comida\ComidaServiceInterface;



return [
    ComidaServiceInterface::class => DI\autowire(ComidaService::class),
    ComidaRepositoryInterface::class => DI\autowire(ComidaRepository::class),
    AlimentoRepositoryInterface::class => DI\autowire(AlimentoRepository::class),

];

?>