<?php

declare(strict_types=1);

namespace App\Console\Commands\Product;

use Gacela\Framework\DocBlockResolverAwareTrait;
use Src\Product\ProductFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method ProductFacade getFacade()
 */
final class ListProductCommand extends Command
{
    use DocBlockResolverAwareTrait;

    protected static $defaultName = 'gacela:product:list';

    protected function configure(): void
    {
        $this->setDescription('List all products');
    }

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
