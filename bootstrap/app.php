<?php

use App\Console\Commands\CreateSqliteFileCommand;
use App\Console\Commands\Product\AddProductCommand;
use App\Console\Commands\Product\ListProductCommand;
use Gacela\Console\Infrastructure\Command\DebugGraphCommand;
use Gacela\Console\Infrastructure\Command\DebugModuleCommand;
use Gacela\Console\Infrastructure\Command\ListModulesCommand;
use Gacela\Console\Infrastructure\Command\MakeModuleCommand;
use Gacela\Framework\Gacela;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        // Application (Product module) commands
        CreateSqliteFileCommand::class,
        AddProductCommand::class,
        ListProductCommand::class,
        // Gacela module dev tooling, exposed through Laravel's artisan
        MakeModuleCommand::class,
        ListModulesCommand::class,
        DebugModuleCommand::class,
        DebugGraphCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Boot Gacela: it reads gacela.php at the project root, which loads the app
// config (config/*.php + .env*) and registers the module bindings, so the
// Facades can resolve their Factories and dependencies.
Gacela::bootstrap($app->basePath());

return $app;
