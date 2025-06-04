<?php

    use App\Modules\Alimento\AlimentoRepository;
    use App\Modules\Alimento\AlimentoRepositoryInterface;
    use App\Modules\Alimento\AlimentoService;
    use App\Modules\Alimento\AlimentoServiceInterface;

    return[
        AlimentoServiceInterface::class  => DI\autowire(AlimentoService::class),
        AlimentoRepositoryInterface::class => DI\autowire(AlimentoRepository::class),
    ];

?>