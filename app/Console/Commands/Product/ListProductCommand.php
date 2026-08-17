<?php

declare(strict_types=1);

namespace App\Console\Commands\Product;

use Gacela\Framework\ServiceResolver\ServiceMap;
use Gacela\Framework\ServiceResolverAwareTrait;
use Src\Product\ProductFacade;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function sprintf;

/**
 * @method ProductFacade getFacade()
 */
#[ServiceMap(method: 'getFacade', className: ProductFacade::class)]
#[AsCommand(name: 'product:list', description: 'List all products')]
final class ListProductCommand extends Command
{
    use ServiceResolverAwareTrait;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = $this->getFacade()->getAllProducts();

        foreach ($products as $product) {
            $output->writeln(sprintf(
                'Product name: %s, price: %s',
                $product->name,
                $product->price,
            ));
        }

        return Command::SUCCESS;
    }
}
