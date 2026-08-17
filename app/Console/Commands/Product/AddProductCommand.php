<?php

declare(strict_types=1);

namespace App\Console\Commands\Product;

use Gacela\LaravelBridge\Attribute\Inject;
use Illuminate\Console\Command;
use Src\Product\Infrastructure\PriceInput;
use Src\Product\ProductFacade;

/**
 * Reaches the Facade through the bridge's #[Inject]: Laravel resolves the
 * parameter through Gacela's container instead of autowiring it itself. The
 * class is required on a constructor parameter -- Laravel hands a contextual
 * attribute no parameter to read a type from.
 *
 * Note the parameter is *not* promoted. A promoted parameter carries its
 * attributes onto the property as well, and the bridge's afterResolving
 * listener then treats this constructor injection as a property injection --
 * which for a readonly property throws.
 */
final class AddProductCommand extends Command
{
    protected $signature = 'product:add {name} {price?}';

    protected $description = 'Add new product';

    private readonly ProductFacade $facade;

    public function __construct(
        #[Inject(ProductFacade::class)] ProductFacade $facade,
    ) {
        parent::__construct();

        $this->facade = $facade;
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
