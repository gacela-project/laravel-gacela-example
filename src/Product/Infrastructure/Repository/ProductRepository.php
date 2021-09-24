<?php

declare(strict_types=1);

namespace Src\Product\Infrastructure\Repository;

use App\Models\Product;
use Src\Product\Domain\ProductEntityManagerInterface;
use Src\Product\Domain\ProductRepositoryInterface;

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
