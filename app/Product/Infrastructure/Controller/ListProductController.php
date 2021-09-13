<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Controller;

use App\Product\ProductFacade;
use App\Shared\Infrastructure\Http\Controllers\Controller;
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
