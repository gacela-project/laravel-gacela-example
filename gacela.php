<?php

use Gacela\Framework\AbstractConfigGacela;
use Gacela\Framework\Config\ConfigReader\EnvConfigReader;
use Gacela\Framework\Config\GacelaConfigBuilder\ConfigBuilder;
use Gacela\Framework\Config\GacelaConfigBuilder\MappingInterfacesBuilder;
use Src\Product\Domain\ProductRepositoryInterface;
use Src\Product\Infrastructure\Repository\ProductRepository;

return static fn() => new class() extends AbstractConfigGacela {
    public function config(ConfigBuilder $configBuilder): void
    {
        $configBuilder->add('.env*', '.env', EnvConfigReader::class);
        $configBuilder->add('config/*.php');
    }

    public function mappingInterfaces(MappingInterfacesBuilder $mappingInterfacesBuilder, array $globalServices): void
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = $globalServices['laravel/app'];

        $mappingInterfacesBuilder->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }
};
