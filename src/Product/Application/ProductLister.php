<?php

declare(strict_types=1);

namespace Src\Product\Application;

use Src\Product\Domain\ProductRepositoryInterface;
use App\Models\Product;

final class ProductLister
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return list<Product>
     */
    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }
}
