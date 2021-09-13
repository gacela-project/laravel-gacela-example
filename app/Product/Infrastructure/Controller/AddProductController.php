<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Controller;

use App\Product\ProductFacade;
use App\Shared\Infrastructure\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

final class AddProductController extends Controller
{
    private ProductFacade $productFacade;

    public function __construct(ProductFacade $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    public function __invoke(string $name): RedirectResponse
    {
        $price = 123456;
        $this->productFacade->createNewProduct($name, $price);

        return Redirect::to('list')->with('success', "The product {$name} has been created.");
    }
}
