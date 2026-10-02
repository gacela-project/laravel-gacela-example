<?php

declare(strict_types=1);

namespace Src\Product;

use Gacela\Framework\AbstractFactory;
use Src\Product\Application\CreatedProducts;
use Src\Product\Application\ProductCreator;
use Src\Product\Application\ProductLister;
use Src\Product\Domain\ProductRepositoryInterface;

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
            $this->getConfig()->getDefaultProductPrice(),
            $this->getCreatedProducts()
        );
    }

    public function getCreatedProducts(): CreatedProducts
    {
        return $this->singleton(CreatedProducts::class, static fn (): CreatedProducts => new CreatedProducts);
    }

    public function createProductLister(): ProductLister
    {
        return new ProductLister($this->productRepository);
    }
}
