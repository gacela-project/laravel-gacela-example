<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Gacela\Framework\ServiceResolver\ServiceMap;
use Gacela\Framework\ServiceResolverAwareTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Src\Product\Infrastructure\PriceInput;
use Src\Product\ProductFacade;

/**
 * @method ProductFacade getFacade()
 */
#[ServiceMap(method: 'getFacade', className: ProductFacade::class)]
final class AddProductController extends Controller
{
    use ServiceResolverAwareTrait;

    public function __invoke(string $name, ?string $price = null): RedirectResponse
    {
        $this->getFacade()->createNewProduct($name, PriceInput::parse($price));

        return Redirect::to('list')->with('success', "The product {$name} has been created.");
    }
}
