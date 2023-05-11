<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_listing_is_empty(): void
    {
        $response = $this->get('/list');

        $response->assertSee('No products have been found');
    }

    public function test_create_and_list_products(): void
    {
        $this->get('/add/product1')->assertRedirect('/list');
        $this->get('/add/product2/100')->assertRedirect('/list');

        $response = $this->get('/list');

        $response->assertSee('product1 - 49');
        $response->assertSee('product2 - 100');

        $this->assertDatabaseHas('products', ['id' => 1, 'name' => 'product1', 'price' => 49]);
        $this->assertDatabaseHas('products', ['id' => 2, 'name' => 'product2', 'price' => 100]);
    }
}
