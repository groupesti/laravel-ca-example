<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

final class CaDemoSetupCommand extends Command
{
    protected $signature = 'ca-example:setup
        {--fresh : Drop all tables before migrating}';

    protected $description = 'Set up the CA demo application (migrations + seed data)';

    public function handle(): int
    {
        $this->info('Setting up CA Demo Application...');
        $this->newLine();

        // Ensure SQLite database file exists
        $dbPath = database_path('database.sqlite');

        if (! file_exists($dbPath)) {
            touch($dbPath);
            $this->components->info("Created SQLite database: {$dbPath}");
        }

        // Run migrations
        $this->components->task('Running migrations', function (): void {
            $args = $this->option('fresh')
                ? ['--force' => true]
                : ['--force' => true];

            if ($this->option('fresh')) {
                Artisan::call('migrate:fresh', $args, $this->getOutput());
            } else {
                Artisan::call('migrate', $args, $this->getOutput());
            }
        });

        $this->newLine();

        // Run the demo seeder
        $this->components->task('Seeding demo data', function (): void {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\CaDemoSeeder',
                '--force' => true,
            ], $this->getOutput());
        });

        $this->newLine();
        $this->displaySummary();

        return self::SUCCESS;
    }

    private function displaySummary(): void
    {
        $this->info('Setup complete! Summary:');
        $this->newLine();

        $stats = [
            ['Certificate Authorities', \CA\Models\CertificateAuthority::count()],
            ['Certificates', \CA\Crt\Models\Certificate::count()],
            ['Keys', \CA\Key\Models\Key::count()],
            ['Audit Entries', \CA\Models\AuditLog::count()],
        ];

        $this->table(['Resource', 'Count'], $stats);

        $this->newLine();
        $this->info('Available routes:');
        $this->line('  Dashboard:      http://localhost:8000/');
        $this->line('  CAs:            http://localhost:8000/cas');
        $this->line('  Certificates:   http://localhost:8000/certificates');
        $this->line('  Audit Log:      http://localhost:8000/audit');
        $this->newLine();
        $this->line('Run: php artisan serve');
    }
}
