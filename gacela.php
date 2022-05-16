<?php

use Gacela\Framework\Bootstrap\GacelaConfig;
use Gacela\Framework\Config\ConfigReader\EnvConfigReader;
use Src\Product\Domain\ProductRepositoryInterface;
use Src\Product\Infrastructure\Repository\ProductRepository;

return static function(GacelaConfig $config) {
    /** @var \Illuminate\Foundation\Application $app */
    $app = $config->getExternalService('laravel/app');

    $config
        ->addAppConfig('.env*', '.env', EnvConfigReader::class)
        ->addAppConfig('config/*.php')
        ->addMappingInterface(ProductRepositoryInterface::class, ProductRepository::class);
};
