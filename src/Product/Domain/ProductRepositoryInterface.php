<?php

declare(strict_types=1);

namespace Src\Product\Domain;

interface ProductRepositoryInterface
{
    public function save(ProductTransfer $productTransfer): void;

    /**
     * @return list<ProductTransfer>
     */
    public function findAll(): array;
}
