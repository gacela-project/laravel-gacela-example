<?php

declare(strict_types=1);

namespace App\Console\Infrastructure;

use App\Product\Infrastructure\Console\AddProductCommand;
use App\Product\Infrastructure\Console\ListProductCommand;
use App\Shared\Infrastructure\Console\Commands\HelloCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    protected $commands = [
        HelloCommand::class,
        AddProductCommand::class,
        ListProductCommand::class,
    ];

    protected function commands(): void
    {
        require __DIR__ . '/console.php';
    }
}
