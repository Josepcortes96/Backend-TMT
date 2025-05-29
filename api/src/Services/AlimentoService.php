<?php

namespace App\Services;

use App\Services\Interfaces\AlimentoServiceInterface;
use App\Repositories\Interfaces\AlimentoRepositoryInterface;

class AlimentoService implements AlimentoServiceInterface
{
    private AlimentoRepositoryInterface $alimentoRepository;

    public function __construct(AlimentoRepositoryInterface $alimentoRepository)
    {
        $this->alimentoRepository = $alimentoRepository;
    }

    public function createAlimento(
        string $nombre,
        float $calorias,
        float $proteinas,
        float $carbohidratos,
        float $grasas,
        string $familia,
        float $agua,
        float $fibra,
        string $categoria
    ): bool {
        return $this->alimentoRepository->createAlimento(
            $nombre, $calorias, $proteinas, $carbohidratos, $grasas, $familia, $agua, $fibra, $categoria
        );
    }

    public function getAlimentoPorId(int $id): ?array
    {
        return $this->alimentoRepository->getAlimentoPorId($id);
    }

    public function getAlimentoPorName(string $nombre): ?array
    {
        return $this->alimentoRepository->getAlimentoPorName($nombre);
    }

    public function getAlimentosFamilia(string $familia): ?array
    {
        return $this->alimentoRepository->getAlimentosFamilia($familia);
    }

    public function calcularValoresNutricionales(array $alimento, float $cantidad): array
    {
        return $this->alimentoRepository->calcularValoresNutricionales($alimento, $cantidad);
    }

    public function getAlimentos(): array
    {
        return $this->alimentoRepository->getAlimentos();
    }
}
?>