<?php

declare(strict_types = 1);

namespace App\Console\Infrastructure;

use App\Calculator\Infrastructure\Console\Commands\AdderCommand;
use App\Shared\Infrastructure\Console\Commands\HelloCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    protected $commands = [
        HelloCommand::class,
        AdderCommand::class,
    ];

    protected function commands(): void
    {
        require __DIR__ . '/console.php';
    }
}
