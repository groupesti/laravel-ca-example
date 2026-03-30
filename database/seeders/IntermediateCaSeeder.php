<?php

declare(strict_types=1);

namespace Database\Seeders;

use CA\DTOs\DistinguishedName;
use CA\Models\CertificateAuthority;
use CA\Models\CertificateType;
use CA\Models\KeyAlgorithm;
use CA\Services\CaManager;
use Illuminate\Database\Seeder;

final class IntermediateCaSeeder extends Seeder
{
    public function run(): void
    {
        /** @var CaManager $caManager */
        $caManager = app(CaManager::class);

        $rootCa = CertificateAuthority::query()
            ->whereNull('parent_id')
            ->firstOrFail();

        $algorithm = KeyAlgorithm::fromSlug(KeyAlgorithm::RSA_4096);

        // --- TLS Intermediate CA ---
        $tlsDn = new DistinguishedName(
            commonName: 'Demo TLS Intermediate CA',
            organization: 'Groupe STI',
            organizationalUnit: 'TLS Services',
            country: 'CA',
        );

        $tlsCa = $caManager->createIntermediateCA(
            parent: $rootCa,
            dn: $tlsDn,
            algorithm: $algorithm,
            validityDays: 1825,
            pathLength: 0,
        );

        $this->command?->info("TLS Intermediate CA created: {$tlsCa->id}");
        $this->command?->line("  Subject: {$tlsDn->toString()}");

        // --- Code Signing Intermediate CA ---
        $csDn = new DistinguishedName(
            commonName: 'Demo Code Signing CA',
            organization: 'Groupe STI',
            organizationalUnit: 'Code Signing',
            country: 'CA',
        );

        $csCa = $caManager->createIntermediateCA(
            parent: $rootCa,
            dn: $csDn,
            algorithm: $algorithm,
            validityDays: 1825,
            pathLength: 0,
        );

        $this->command?->info("Code Signing Intermediate CA created: {$csCa->id}");
        $this->command?->line("  Subject: {$csDn->toString()}");
    }
}
