<?php

declare(strict_types=1);

namespace App\Product\Domain;

use App\Shared\Infrastructure\Models\Product;

interface ProductEntityManagerInterface
{
    public function save(Product $product): void;
}
