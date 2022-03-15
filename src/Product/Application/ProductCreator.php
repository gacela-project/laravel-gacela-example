<?php

declare(strict_types=1);

namespace Src\Product\Application;

use Src\Product\Domain\ProductRepositoryInterface;
use Src\Product\Domain\ProductTransfer;

final class ProductCreator
{
    private ProductRepositoryInterface $productRepository;

    private int $defaultPrice;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        int $defaultPrice
    ) {
        $this->productRepository = $productRepository;
        $this->defaultPrice = $defaultPrice;
    }

    public function createProduct(string $name, ?int $price = null): void
    {
        $product = new ProductTransfer();
        $product->name = $name;
        $product->price = $price ?? $this->defaultPrice;

        $this->productRepository->save($product);
        # send events, emails, or whatever
    }
}
