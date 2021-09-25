<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

final class HelloWorldCommand extends Command
{
    protected $signature = 'gacela:hello';

    protected $description = 'Hello world command';

    public function handle(): int
    {
        $this->output->writeln('Hello Gacela!');

        return 0;
    }
}
