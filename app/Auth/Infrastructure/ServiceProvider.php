<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as SupportServiceProvider;
use Illuminate\Support\Facades\Gate;

final class ServiceProvider extends SupportServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        // 'App\Main\Infrastructure\Models\Model' => 'App\Main\Infrastructure\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
