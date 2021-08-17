<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

final class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     */
    protected $except = [
        //
    ];
}
