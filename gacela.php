<?php

use App\Product\Domain\ProductEntityManagerInterface;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Infrastructure\Repository\ProductRepository;
use Gacela\Framework\AbstractConfigGacela;

return static fn(array $services) => new class($services) extends AbstractConfigGacela {
    public function config(): array
    {
        return [
            [
                'type' => 'env',
                'path' => '.env*',
                'path_local' => '.env',
            ],
            [
                'type' => 'php',
                'path' => 'config/*.php',
            ],
        ];
    }

    public function mappingInterfaces(): array
    {
        return [
            ProductRepositoryInterface::class => ProductRepository::class,
            ProductEntityManagerInterface::class => ProductRepository::class,
        ];
    }
};
