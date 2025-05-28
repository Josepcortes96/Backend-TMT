<?php

use App\Repositories\Interfaces\CentroRepositoryInterface;
use App\Repositories\CentroRepository;
use App\Services\Interfaces\CentroServiceInterface;
use App\Services\CentroService;

return [
    CentroRepositoryInterface::class => DI\autowire(CentroRepository::class),
    CentroServiceInterface::class => DI\autowire(CentroService::class),
];

?>