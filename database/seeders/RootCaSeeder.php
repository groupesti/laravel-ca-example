<?php

declare(strict_types=1);

namespace Database\Seeders;

use CA\DTOs\DistinguishedName;
use CA\Models\KeyAlgorithm;
use CA\Services\CaManager;
use Illuminate\Database\Seeder;

final class RootCaSeeder extends Seeder
{
    public function run(): void
    {
        /** @var CaManager $caManager */
        $caManager = app(CaManager::class);

        $dn = new DistinguishedName(
            commonName: 'Demo Root CA',
            organization: 'Groupe STI',
            country: 'CA',
        );

        $algorithm = KeyAlgorithm::fromSlug(KeyAlgorithm::RSA_4096);

        $ca = $caManager->createRootCA(
            dn: $dn,
            algorithm: $algorithm,
            validityDays: 3650,
            pathLength: 2,
        );

        $this->command?->info("Root CA created: {$ca->id}");
        $this->command?->line("  Subject: {$dn->toString()}");
        $this->command?->line("  Serial: {$ca->serial_number}");
        $this->command?->line("  Valid until: {$ca->not_after->toDateString()}");
    }
}
