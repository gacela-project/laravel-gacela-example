<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Src\Product\ProductFacade;
use Illuminate\Console\Command;

final class AddProductCommand extends Command
{
    protected $signature = 'gacela:product:add {name} {price?}';

    protected $description = 'Add new product';

    private ProductFacade $productFacade;

    public function __construct(ProductFacade $productFacade)
    {
        parent::__construct();
        $this->productFacade = $productFacade;
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $price = $this->argument('price');

        $this->productFacade->createNewProduct($name, $this->validatePriceInput($price));

        $this->output->writeln($name . ' product created successfully');

        return self::SUCCESS;
    }

    private function validatePriceInput(?string $price): ?int
    {
        if ($price === null) {
            return null;
        }

        if (filter_var($price, FILTER_VALIDATE_INT) === 0 || !filter_var($price, FILTER_VALIDATE_INT) === false) {
            return (int) $price;
        }

        throw new \RuntimeException('Second parameter [price] must be of type integer');
    }
}
