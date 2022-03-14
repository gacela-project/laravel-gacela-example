<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Src\Product\ProductFacade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

final class AddProductController extends Controller
{
    private ProductFacade $productFacade;

    public function __construct(ProductFacade $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    public function __invoke(string $name, string $price = null): RedirectResponse
    {
        $this->productFacade->createNewProduct($name, $this->validatePriceInput($price));

        return Redirect::to('list')->with('success', "The product {$name} has been created.");
    }

    private function validatePriceInput(?string $price): ?int
    {
        if ($price === null) {
            return null;
        }

        if (filter_var($price, FILTER_VALIDATE_INT) === 0 || !filter_var($price, FILTER_VALIDATE_INT) === false) {
            return (int) $price;
        }

        throw new \RuntimeException('Second parameter [price] must be of type integer');
    }
}
