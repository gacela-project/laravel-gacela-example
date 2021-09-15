<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repository;

use App\Product\Domain\ProductEntityManagerInterface;
use App\Product\Domain\ProductRepositoryInterface;
use App\Shared\Infrastructure\Models\Product;

final class ProductRepository implements ProductRepositoryInterface, ProductEntityManagerInterface
{
    public function save(Product $product): void
    {
        $product->save();
    }

    public function findAll(): array
    {
        return Product::all()->all();
    }
}
