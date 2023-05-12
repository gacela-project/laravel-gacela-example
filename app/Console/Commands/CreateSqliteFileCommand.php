<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

final class CreateSqliteFileCommand extends Command
{
    protected $signature = 'gacela:create-sqlite';

    public function handle(): int
    {
        $databaseSqliteFile = base_path('database') . '/database.sqlite';

        if (!file_exists($databaseSqliteFile)) {
            touch($databaseSqliteFile);
        }

        if ('true' === $this->choice('Run migrations?', ['true', 'false'], 'true')) {
            Artisan::call('migrate', ['--force' => true]);

            $this->line(Artisan::output());
        }

        return self::SUCCESS;
    }
}
