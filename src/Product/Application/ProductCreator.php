<?php

declare(strict_types=1);

namespace Src\Product\Application;

use Src\Product\Domain\ProductEntityManagerInterface;
use App\Models\Product;

final class ProductCreator
{
    private ProductEntityManagerInterface $productEntityManager;

    private int $defaultPrice;

    public function __construct(
        ProductEntityManagerInterface $productEntityManager,
        int  $defaultPrice
    ) {
        $this->productEntityManager = $productEntityManager;
        $this->defaultPrice = $defaultPrice;
    }

    public function createProduct(string $name, ?int $price = null): void
    {
        $product = new Product();
        $product->name = $name;
        $product->price = $price ?? $this->defaultPrice;

        $this->productEntityManager->save($product);
        # send events, emails, or whatever
    }
}
