<?php

use Gacela\Framework\AbstractConfigGacela;
use Src\Product\Domain\ProductEntityManagerInterface;
use Src\Product\Domain\ProductRepositoryInterface;
use Src\Product\Infrastructure\Repository\ProductRepository;

return static fn() => new class() extends AbstractConfigGacela {
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

    public function mappingInterfaces(array $globalServices): array
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = $globalServices['laravel/app'];

        return [
            ProductRepositoryInterface::class => ProductRepository::class,
            ProductEntityManagerInterface::class => ProductRepository::class,
        ];
    }
};
