<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Gacela\Framework\ServiceResolverAwareTrait;
use Illuminate\View\View;
use Src\Product\ProductFacade;

/**
 * @method ProductFacade getFacade()
 */
final class ListProductController extends Controller
{
    use ServiceResolverAwareTrait;

    public function __invoke(): View
    {
        $products = $this->getFacade()->getAllProducts();

        return view(
            '/list-product/index',
            ['products' => $products]
        );
    }
}
