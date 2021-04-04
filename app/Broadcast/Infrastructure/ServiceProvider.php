<?php

declare(strict_types = 1);

namespace App\Broadcast\Infrastructure;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

final class ServiceProvider extends SupportServiceProvider
{
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Broadcast::routes();

        require __DIR__ . '/routes/channels.php';
    }
}
