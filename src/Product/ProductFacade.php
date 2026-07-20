<?php

declare(strict_types=1);

namespace Src\Product;

use Gacela\Framework\AbstractFacade;
use Src\Product\Domain\ProductTransfer;

/**
 * @method ProductFactory getFactory()
 */
final class ProductFacade extends AbstractFacade
{
    public function createNewProduct(string $name, ?int $price = null): void
    {
        $this->getFactory()
            ->createProductCreator()
            ->createProduct($name, $price);
    }

    /**
     * @return list<ProductTransfer>
     */
    public function getAllProducts(): array
    {
        return $this->getFactory()
            ->createProductLister()
            ->getAllProducts();
    }
}
