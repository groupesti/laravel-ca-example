<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use CA\Crl\Services\CrlManager;
use CA\Crt\Models\Certificate;
use CA\Crt\Services\CertificateManager;
use CA\Csr\Services\CsrBuilder;
use CA\DTOs\CertificateOptions;
use CA\DTOs\DistinguishedName;
use CA\Key\Contracts\KeyManagerInterface;
use CA\Models\CertificateAuthority;
use CA\Models\CertificateType;
use CA\Models\KeyAlgorithm;
use CA\Models\RevocationReason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DemoActionController extends Controller
{
    public function issueCertificate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ca_id' => 'required|string|exists:certificate_authorities,id',
            'common_name' => 'required|string|max:255',
            'type' => 'required|string',
        ]);

        /** @var CertificateAuthority $ca */
        $ca = CertificateAuthority::findOrFail($validated['ca_id']);

        /** @var KeyManagerInterface $keyManager */
        $keyManager = app(KeyManagerInterface::class);

        /** @var CertificateManager $certManager */
        $certManager = app(CertificateManager::class);

        $algorithm = KeyAlgorithm::fromSlug(KeyAlgorithm::RSA_2048);

        $key = $keyManager->generate(
            algorithm: $algorithm,
            params: ['ca_id' => $ca->id],
            tenantId: $ca->tenant_id,
        );

        $dn = new DistinguishedName(
            commonName: $validated['common_name'],
            organization: 'Groupe STI',
            country: 'CA',
        );

        /** @var CsrBuilder $csrBuilder */
        $csrBuilder = app(CsrBuilder::class);

        $builder = $csrBuilder->subject($dn)->key($key);

        if ($validated['type'] === CertificateType::SERVER_TLS) {
            $builder->addDnsName($validated['common_name']);
        }

        $csr = $builder->build();

        $certType = CertificateType::fromSlug($validated['type']);

        $keyUsage = match ($validated['type']) {
            CertificateType::SERVER_TLS => ['digitalSignature', 'keyEncipherment'],
            CertificateType::CLIENT_MTLS => ['digitalSignature'],
            CertificateType::CODE_SIGNING => ['digitalSignature'],
            default => ['digitalSignature'],
        };

        $extendedKeyUsage = match ($validated['type']) {
            CertificateType::SERVER_TLS => ['serverAuth'],
            CertificateType::CLIENT_MTLS => ['clientAuth'],
            CertificateType::CODE_SIGNING => ['codeSigning'],
            default => [],
        };

        $sans = $validated['type'] === CertificateType::SERVER_TLS
            ? [['dNSName' => $validated['common_name']]]
            : null;

        $options = new CertificateOptions(
            type: $certType,
            validityDays: 365,
            keyUsage: $keyUsage,
            extendedKeyUsage: $extendedKeyUsage,
            subjectAlternativeNames: $sans,
        );

        $cert = $certManager->issueFromCsr(
            ca: $ca,
            csr: $csr,
            options: $options,
        );

        return redirect()
            ->route('certificates.show', $cert->uuid)
            ->with('success', "Certificate issued: {$cert->serial_number}");
    }

    public function revokeCertificate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'certificate_uuid' => 'required|string',
            'reason' => 'required|string',
        ]);

        $certificate = Certificate::query()
            ->where('uuid', $validated['certificate_uuid'])
            ->firstOrFail();

        /** @var CertificateManager $certManager */
        $certManager = app(CertificateManager::class);

        $reason = RevocationReason::fromSlug($validated['reason']);

        $certManager->revoke($certificate, $reason);

        return redirect()
            ->route('certificates.show', $certificate->uuid)
            ->with('success', 'Certificate revoked successfully.');
    }

    public function generateCrl(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ca_id' => 'required|string|exists:certificate_authorities,id',
        ]);

        /** @var CertificateAuthority $ca */
        $ca = CertificateAuthority::findOrFail($validated['ca_id']);

        /** @var CrlManager $crlManager */
        $crlManager = app(CrlManager::class);

        $crl = $crlManager->generate($ca);

        return redirect()
            ->route('cas.show', $ca->id)
            ->with('success', "CRL #{$crl->crl_number} generated with {$crl->entries_count} entries.");
    }
}
