<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Define the application root to the Gacela Config
|--------------------------------------------------------------------------
|
| Finally, we will be able to bootstrap Gacela in order to get the config
| using the $this->get() and resolve the interfaces from the 'gacela.php'.
|
*/

##############################
# OPTION A: Using gacela.php #
##############################
\Gacela\Framework\Gacela::bootstrap(base_path());

############################################################
# OPTION B: Directly here. Without the need for gacela.php #
############################################################

//use Gacela\Framework\Bootstrap\GacelaConfig;
//use Gacela\Framework\Config\ConfigReader\EnvConfigReader;
//use Gacela\Framework\Gacela;
//use Src\Product\Domain\ProductRepositoryInterface;
//use Src\Product\Infrastructure\Repository\ProductRepository;
//
//$configFn = static fn(GacelaConfig $config) => $config
//    ->addAppConfig('.env*', '.env', EnvConfigReader::class)
//    ->addAppConfig('config/*.php')
//    ->addBinding(ProductRepositoryInterface::class, ProductRepository::class);
//
//Gacela::bootstrap(base_path(), $configFn);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
