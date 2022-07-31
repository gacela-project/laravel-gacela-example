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
        $productEntity = new Product();
        $productEntity->name = $productTransfer->getName();
        $productEntity->price = $productTransfer->getPrice();

        $productEntity->save();
    }

    /**
     * @return list<ProductTransfer>
     */
    public function findAll(): array
    {
        return array_map(
            static function(Product $p) {
                $productTransfer = (new ProductTransfer());
                $productTransfer->setPrice((int)$p->price);
                $productTransfer->setName($p->name);

                return $productTransfer;
            },
            Product::all()->all()
        );
    }
}
