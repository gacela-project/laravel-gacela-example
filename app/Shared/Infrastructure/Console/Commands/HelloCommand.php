<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Console\Commands;

use Illuminate\Console\Command;

final class HelloCommand extends Command
{
    protected $signature = 'gacela:hello';

    protected $description = 'Hello world command';

    public function handle(): int
    {
        $this->output->writeln('Hello Gacela!');

        return 0;
    }
}
