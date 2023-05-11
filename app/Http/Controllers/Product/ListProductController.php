<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Gacela\Framework\DocBlockResolverAwareTrait;
use Illuminate\View\View;
use Src\Product\ProductFacade;

/**
 * @method ProductFacade getFacade()
 */
final class ListProductController extends Controller
{
    use DocBlockResolverAwareTrait;

    public function __invoke(): View
    {
        $products = $this->getFacade()->getAllProducts();

        return view(
            '/list-product/index',
            ['products' => $products]
        );
    }
}
