<?php

declare(strict_types=1);

namespace Src\Product;

use Src\Product\Application\ProductCreator;
use Src\Product\Application\ProductLister;
use Src\Product\Domain\ProductRepositoryInterface;
use Gacela\Framework\AbstractFactory;

/**
 * @method ProductConfig getConfig()
 */
final class ProductFactory extends AbstractFactory
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function createProductCreator(): ProductCreator
    {
        return new ProductCreator(
            $this->productRepository,
            $this->getConfig()->getDefaultProductPrice()
        );
    }

    public function createProductLister(): ProductLister
    {
        return new ProductLister($this->productRepository);
    }
}
