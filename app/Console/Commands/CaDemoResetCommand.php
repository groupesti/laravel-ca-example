<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

final class CaDemoResetCommand extends Command
{
    protected $signature = 'ca-example:reset
        {--force : Skip confirmation prompt}';

    protected $description = 'Reset the CA demo application (drop all tables, re-migrate, re-seed)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('This will destroy all data. Continue?')) {
            $this->warn('Aborted.');

            return self::FAILURE;
        }

        $this->info('Resetting CA Demo Application...');
        $this->newLine();

        $this->components->task('Dropping tables and re-migrating', function (): void {
            Artisan::call('migrate:fresh', ['--force' => true], $this->getOutput());
        });

        $this->newLine();

        $this->components->task('Seeding demo data', function (): void {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\CaDemoSeeder',
                '--force' => true,
            ], $this->getOutput());
        });

        $this->newLine();
        $this->info('Demo application has been reset successfully.');

        return self::SUCCESS;
    }
}
