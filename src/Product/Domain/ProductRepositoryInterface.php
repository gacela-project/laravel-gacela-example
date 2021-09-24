<?php

declare(strict_types=1);

namespace Src\Product\Domain;

use App\Models\Product;

interface ProductRepositoryInterface
{
    /**
     * @return list<int, Product>
     */
    public function findAll(): array;
}
