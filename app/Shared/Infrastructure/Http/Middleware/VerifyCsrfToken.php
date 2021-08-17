<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

final class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     */
    protected $except = [
        //
    ];
}
