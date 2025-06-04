<?php

use App\Modules\Centro\CentroRepositoryInterface;
use App\Modules\Centro\CentroRepository;
use App\Modules\Centro\CentroServiceInterface;
use App\Modules\Centro\CentroService;

return [
    CentroRepositoryInterface::class => DI\autowire(CentroRepository::class),
    CentroServiceInterface::class => DI\autowire(CentroService::class),
];

?>