<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Src\Product\ProductFacade;
use Illuminate\Console\Command;

final class AddProductCommand extends Command
{
    protected $signature = 'gacela:product:add {name}';

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

        $this->productFacade->createNewProduct($name);

        $this->output->info($name . ' product created successfully');

        return 0;
    }
}
