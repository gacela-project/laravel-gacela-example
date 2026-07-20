<?php

use App\Console\Commands\CreateSqliteFileCommand;
use App\Console\Commands\Product\AddProductCommand;
use App\Console\Commands\Product\ListProductCommand;
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
        CreateSqliteFileCommand::class,
        AddProductCommand::class,
        ListProductCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

/*
|--------------------------------------------------------------------------
| Bootstrap Gacela
|--------------------------------------------------------------------------
|
| This is where Gacela boots. It reads the "gacela.php" file at the project
| root, which loads the app config (config/*.php and .env*) and resolves the
| module bindings (e.g. ProductRepositoryInterface). Thanks to this, the
| Facades can inject their Factories and resolve everything you need.
|
*/
Gacela::bootstrap($app->basePath());

return $app;
