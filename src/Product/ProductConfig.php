<?php

declare(strict_types=1);

namespace Src\Product;

use Gacela\Framework\AbstractConfig;

final class ProductConfig extends AbstractConfig
{
    public function getDefaultProductPrice(): int
    {
        return (int) $this->getString('DEFAULT_PRODUCT_PRICE');
    }
}
