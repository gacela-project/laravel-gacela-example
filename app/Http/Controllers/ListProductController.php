<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Gacela\Framework\DocBlockResolverAwareTrait;
use Src\Product\ProductFacade;
use Illuminate\View\View;

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
