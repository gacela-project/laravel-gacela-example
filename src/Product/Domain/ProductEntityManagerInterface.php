<?php

declare(strict_types=1);

namespace Src\Product\Domain;

use App\Models\Product;

interface ProductEntityManagerInterface
{
    public function save(Product $product): void;
}
