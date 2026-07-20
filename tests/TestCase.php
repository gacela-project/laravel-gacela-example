<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The example does not ship a compiled asset bundle, so skip resolving
        // the Vite manifest when rendering views under test.
        $this->withoutVite();
    }
}
