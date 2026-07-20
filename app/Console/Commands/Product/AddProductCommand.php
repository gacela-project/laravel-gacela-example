<?php

declare(strict_types=1);

namespace App\Console\Commands\Product;

use Illuminate\Console\Command;
use Src\Product\Infrastructure\PriceInput;
use Src\Product\ProductFacade;

final class AddProductCommand extends Command
{
    protected $signature = 'gacela:product:add {name} {price?}';

    protected $description = 'Add new product';

    public function __construct(
        private readonly ProductFacade $facade,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $name = $this->argument('name');
        $price = $this->argument('price');

        $this->facade->createNewProduct($name, PriceInput::parse($price));

        $this->output->writeln($name.' product created successfully');

        return self::SUCCESS;
    }
}
