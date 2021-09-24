<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Src\Product\ProductFacade;
use Illuminate\View\View;

final class ListProductController extends Controller
{
    private ProductFacade $productFacade;

    public function __construct(ProductFacade $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    public function __invoke(): View
    {
        $products = $this->productFacade->getAllProducts();

        return view(
            '/list-product/index',
            ['products' => $products]
        );
    }
}
