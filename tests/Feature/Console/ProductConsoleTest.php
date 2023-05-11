<?php

declare(strict_types=1);

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductConsoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_is_empty(): void
    {
        $command = $this->artisan('gacela:product:list');

        $command->doesntExpectOutput('Product name');
        $command->assertSuccessful();
    }

    public function test_create_and_list_products(): void
    {
        $this->artisan('gacela:product:add product1');
        $this->artisan('gacela:product:add product2 200');

        $command = $this->artisan('gacela:product:list');

        $command->expectsOutputToContain('Product name: product1, price: 49');
        $command->expectsOutputToContain('Product name: product2, price: 200');

        $this->assertDatabaseHas('products', ['id' => 1, 'name' => 'product1', 'price' => 49]);
        $this->assertDatabaseHas('products', ['id' => 2, 'name' => 'product2', 'price' => 200]);
    }
}
