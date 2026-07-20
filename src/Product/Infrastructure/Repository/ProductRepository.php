<?php

declare(strict_types=1);

namespace Src\Product\Infrastructure\Repository;

use App\Models\Product;
use Src\Product\Domain\ProductRepositoryInterface;
use Src\Product\Domain\ProductTransfer;

final class ProductRepository implements ProductRepositoryInterface
{
    public function save(ProductTransfer $productTransfer): void
    {
        (new Product($productTransfer->toArray()))->save();
    }

    /**
     * @return list<ProductTransfer>
     */
    public function findAll(): array
    {
        return array_map(
            static fn (Product $p) => (new ProductTransfer)->fromArray($p->toArray()),
            Product::all()->all()
        );
    }
}
