<?php

declare(strict_types=1);

namespace App\Calculator\Infrastructure\Console\Commands;

use App\Calculator\Facade;
use Gacela\Framework\Container\Locator;
use Illuminate\Console\Command;

final class AdderCommand extends Command
{
    protected $signature = 'gacela:add {number*}';

    protected $description = 'Add multiple numbers';

    public function handle(): int
    {
        $numberArguments = $this->argument('number');
        $numbers = array_map(static fn (string $n) => (int)$n, $numberArguments);

        $this->output->writeln($this->getFacade()->add(...$numbers));

        return 0;
    }

    public function getFacade(): Facade
    {
        return Locator::getInstance()->get(Facade::class);
    }
}

