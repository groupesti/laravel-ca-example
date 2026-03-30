<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

final class CaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Seeding CA lookup tables...');
        Artisan::call('ca:seed-lookups', [], $this->command?->getOutput());

        $this->command?->newLine();
        $this->command?->info('Creating Root CA...');
        $this->call(RootCaSeeder::class);

        $this->command?->newLine();
        $this->command?->info('Creating Intermediate CAs...');
        $this->call(IntermediateCaSeeder::class);

        $this->command?->newLine();
        $this->command?->info('Creating demo certificates...');
        $this->call(DemoCertificateSeeder::class);

        $this->command?->newLine();
        $this->command?->info('Demo PKI hierarchy seeded successfully.');
    }
}
