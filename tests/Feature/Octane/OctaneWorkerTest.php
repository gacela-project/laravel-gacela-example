<?php

declare(strict_types=1);

namespace Tests\Feature\Octane;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Octane\Events\RequestTerminated;
use Src\Product\ProductFacade;
use Tests\TestCase;

/**
 * Two requests served by one booted application, as an Octane worker serves
 * them, with or without the event Octane fires after each request.
 */
final class OctaneWorkerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_request_does_not_see_the_state_of_the_previous_one(): void
    {
        $this->get('/add/product1')->assertRedirect('/list');
        $this->terminateOctaneRequest();

        $this->get('/add/product2')->assertRedirect('/list');

        self::assertSame(['product2'], (new ProductFacade)->getProductsCreatedInThisRequest());
    }

    public function test_the_state_leaks_into_the_next_request_without_the_octane_event(): void
    {
        $this->get('/add/product1')->assertRedirect('/list');
        $this->get('/add/product2')->assertRedirect('/list');

        self::assertSame(['product1', 'product2'], (new ProductFacade)->getProductsCreatedInThisRequest());
    }

    private function terminateOctaneRequest(): void
    {
        event(new RequestTerminated($this->app, $this->app, Request::create('/'), new Response));
    }
}
