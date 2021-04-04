<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class CalculatorTest extends TestCase
{
    public function testAdderCommand(): void
    {
        $this->artisan('gacela:add 1 2 3')
            ->expectsOutput('6')
            ->assertExitCode(0);
    }
}
