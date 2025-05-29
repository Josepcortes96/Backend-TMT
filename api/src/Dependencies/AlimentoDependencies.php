<?php

    use App\Repositories\AlimentoRepository;
    use App\Repositories\Interfaces\AlimentoRepositoryInterface;
    use App\Services\AlimentoService;
    use App\Services\Interfaces\AlimentoServiceInterface;

    return[
        AlimentoServiceInterface::class  => DI\autowire(AlimentoService::class),
        AlimentoRepositoryInterface::class => DI\autowire(AlimentoRepository::class),
    ];

?>