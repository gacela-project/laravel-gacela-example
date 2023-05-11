<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HomepageTest extends TestCase
{
    public function test_homepage(): void
    {
        $response = $this->get('/');

        $response->assertSee('Github Gacela');
        $response->assertSee('Website');
        $response->assertStatus(200);
    }
}
