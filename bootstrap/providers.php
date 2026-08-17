<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use Gacela\LaravelBridge\GacelaServiceProvider;

return [
    AppServiceProvider::class,
    // Bootstraps Gacela on boot (reading gacela.php from base_path()), adds the
    // gacela:* commands to artisan, and makes `artisan optimize` warm Gacela's
    // caches. Configure it with config/gacela.php.
    GacelaServiceProvider::class,
];
