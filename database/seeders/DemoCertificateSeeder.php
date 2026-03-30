<?php

declare(strict_types=1);

namespace Database\Seeders;

use CA\Crt\Services\CertificateManager;
use CA\Csr\Services\CsrBuilder;
use CA\DTOs\CertificateOptions;
use CA\DTOs\DistinguishedName;
use CA\Key\Contracts\KeyManagerInterface;
use CA\Models\CertificateAuthority;
use CA\Models\CertificateType;
use CA\Models\KeyAlgorithm;
use Illuminate\Database\Seeder;

final class DemoCertificateSeeder extends Seeder
{
    public function run(): void
    {
        /** @var KeyManagerInterface $keyManager */
        $keyManager = app(KeyManagerInterface::class);

        /** @var CertificateManager $certManager */
        $certManager = app(CertificateManager::class);

        $rootCa = CertificateAuthority::query()
            ->whereNull('parent_id')
            ->firstOrFail();

        // Generate and store the Root CA self-signed certificate + key
        $rootAlgorithm = KeyAlgorithm::fromSlug(KeyAlgorithm::RSA_4096);
        $rootKey = $keyManager->generate(
            algorithm: $rootAlgorithm,
            params: ['ca_id' => $rootCa->id],
        );

        $rootCertOptions = new CertificateOptions(
            type: CertificateType::fromSlug(CertificateType::ROOT_CA),
            validityDays: 3650,
            keyUsage: ['keyCertSign', 'cRLSign'],
            isCa: true,
            pathLength: 2,
        );

        $rootCert = $certManager->issueSelfSigned(
            ca: $rootCa,
            key: $rootKey,
            options: $rootCertOptions,
        );

        $this->command?->info("Root CA self-signed certificate: {$rootCert->serial_number}");

        // --- Intermediate CAs ---
        $intermediateCas = CertificateAuthority::query()
            ->where('parent_id', $rootCa->id)
            ->get();

        $tlsCa = $intermediateCas->first(
            fn (CertificateAuthority $ca): bool => str_contains($ca->subject_dn['CN'] ?? '', 'TLS'),
        );

        $csCa = $intermediateCas->first(
            fn (CertificateAuthority $ca): bool => str_contains($ca->subject_dn['CN'] ?? '', 'Code Signing'),
        );

        // Generate keys and intermediate certs
        if ($tlsCa !== null) {
            $tlsKey = $keyManager->generate(
                algorithm: $rootAlgorithm,
                params: ['ca_id' => $tlsCa->id],
            );

            $tlsDn = DistinguishedName::fromArray($tlsCa->subject_dn);

            $tlsIntCertOptions = new CertificateOptions(
                type: CertificateType::fromSlug(CertificateType::INTERMEDIATE_CA),
                validityDays: 1825,
                keyUsage: ['keyCertSign', 'cRLSign'],
                isCa: true,
                pathLength: 0,
            );

            $tlsIntCert = $certManager->issueIntermediate(
                parentCa: $rootCa,
                dn: $tlsDn,
                key: $tlsKey,
                options: $tlsIntCertOptions,
            );

            $this->command?->info("TLS Intermediate certificate: {$tlsIntCert->serial_number}");

            // --- TLS Server Certificate ---
            $this->issueEndEntity(
                keyManager: $keyManager,
                certManager: $certManager,
                ca: $tlsCa,
                dn: new DistinguishedName(
                    commonName: 'demo.example.com',
                    organization: 'Groupe STI',
                    country: 'CA',
                ),
                type: CertificateType::SERVER_TLS,
                sanDns: ['demo.example.com', 'www.demo.example.com'],
                keyUsage: ['digitalSignature', 'keyEncipherment'],
                extendedKeyUsage: ['serverAuth'],
                label: 'TLS Server',
            );

            // --- Client mTLS Certificate ---
            $this->issueEndEntity(
                keyManager: $keyManager,
                certManager: $certManager,
                ca: $tlsCa,
                dn: new DistinguishedName(
                    commonName: 'Demo User',
                    organization: 'Groupe STI',
                    emailAddress: 'demo.user@example.com',
                    country: 'CA',
                ),
                type: CertificateType::CLIENT_MTLS,
                keyUsage: ['digitalSignature'],
                extendedKeyUsage: ['clientAuth'],
                label: 'Client mTLS',
            );
        }

        if ($csCa !== null) {
            $csKey = $keyManager->generate(
                algorithm: $rootAlgorithm,
                params: ['ca_id' => $csCa->id],
            );

            $csDn = DistinguishedName::fromArray($csCa->subject_dn);

            $csIntCertOptions = new CertificateOptions(
                type: CertificateType::fromSlug(CertificateType::INTERMEDIATE_CA),
                validityDays: 1825,
                keyUsage: ['keyCertSign', 'cRLSign'],
                isCa: true,
                pathLength: 0,
            );

            $csIntCert = $certManager->issueIntermediate(
                parentCa: $rootCa,
                dn: $csDn,
                key: $csKey,
                options: $csIntCertOptions,
            );

            $this->command?->info("Code Signing Intermediate certificate: {$csIntCert->serial_number}");

            // --- Code Signing Certificate ---
            $this->issueEndEntity(
                keyManager: $keyManager,
                certManager: $certManager,
                ca: $csCa,
                dn: new DistinguishedName(
                    commonName: 'Demo Developer',
                    organization: 'Groupe STI',
                    emailAddress: 'dev@example.com',
                    country: 'CA',
                ),
                type: CertificateType::CODE_SIGNING,
                keyUsage: ['digitalSignature'],
                extendedKeyUsage: ['codeSigning'],
                label: 'Code Signing',
            );
        }
    }

    /**
     * @param array<int, string> $sanDns
     * @param array<int, string> $keyUsage
     * @param array<int, string> $extendedKeyUsage
     */
    private function issueEndEntity(
        KeyManagerInterface $keyManager,
        CertificateManager $certManager,
        CertificateAuthority $ca,
        DistinguishedName $dn,
        string $type,
        array $keyUsage = [],
        array $extendedKeyUsage = [],
        array $sanDns = [],
        string $label = 'Certificate',
    ): void {
        $algorithm = KeyAlgorithm::fromSlug(KeyAlgorithm::RSA_2048);

        $key = $keyManager->generate(
            algorithm: $algorithm,
            params: ['ca_id' => $ca->id],
            tenantId: $ca->tenant_id,
        );

        /** @var CsrBuilder $csrBuilder */
        $csrBuilder = app(CsrBuilder::class);

        $builder = $csrBuilder
            ->subject($dn)
            ->key($key);

        foreach ($sanDns as $dns) {
            $builder->addDnsName($dns);
        }

        $csr = $builder->build();

        $certType = CertificateType::fromSlug($type);

        $sans = array_map(
            static fn (string $dns): array => ['dNSName' => $dns],
            $sanDns,
        );

        $options = new CertificateOptions(
            type: $certType,
            validityDays: 365,
            keyUsage: $keyUsage,
            extendedKeyUsage: $extendedKeyUsage,
            subjectAlternativeNames: $sans !== [] ? $sans : null,
        );

        $cert = $certManager->issueFromCsr(
            ca: $ca,
            csr: $csr,
            options: $options,
        );

        $this->command?->info("{$label} certificate issued: {$cert->serial_number}");
        $this->command?->line("  Subject: {$dn->toString()}");
    }
}
