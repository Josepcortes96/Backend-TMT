<?php

use App\Modules\Dato\DatoRepository;
use App\Modules\Dato\DatoRepositoryInterface;
use App\Modules\Dato\DatoService;
use App\Modules\Dato\DatoServiceInterface;

return[
    DatoServiceInterface::class => DI\autowire(DatoService::class),
    DatoRepositoryInterface::class => DI\autowire(DatoRepository::class),
]
?>