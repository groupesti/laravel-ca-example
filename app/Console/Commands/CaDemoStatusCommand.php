<?php

declare(strict_types=1);

namespace App\Console\Commands;

use CA\Crl\Models\Crl;
use CA\Crt\Models\Certificate;
use CA\Key\Models\Key;
use CA\Models\AuditLog;
use CA\Models\CertificateAuthority;
use CA\Models\CertificateStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

final class CaDemoStatusCommand extends Command
{
    protected $signature = 'ca-example:status';

    protected $description = 'Display the current status of the CA demo application';

    public function handle(): int
    {
        $this->info('CA Demo Application Status');
        $this->newLine();

        if (! Schema::hasTable('certificate_authorities')) {
            $this->warn('Database not initialized. Run: php artisan ca-example:setup');

            return self::FAILURE;
        }

        // Resource counts
        $totalCas = CertificateAuthority::count();
        $rootCas = CertificateAuthority::whereNull('parent_id')->count();
        $intermediateCas = CertificateAuthority::whereNotNull('parent_id')->count();

        $totalCerts = Certificate::count();
        $activeCerts = Certificate::where('status', CertificateStatus::ACTIVE)->count();
        $revokedCerts = Certificate::where('status', CertificateStatus::REVOKED)->count();

        $totalKeys = Key::count();
        $totalCrls = Crl::count();
        $auditEntries = AuditLog::count();

        $this->table(
            ['Resource', 'Count', 'Details'],
            [
                ['Certificate Authorities', $totalCas, "Root: {$rootCas}, Intermediate: {$intermediateCas}"],
                ['Certificates', $totalCerts, "Active: {$activeCerts}, Revoked: {$revokedCerts}"],
                ['Keys', $totalKeys, ''],
                ['CRLs', $totalCrls, ''],
                ['Audit Entries', $auditEntries, ''],
            ],
        );

        $this->newLine();

        // CA hierarchy
        $this->info('CA Hierarchy:');
        $roots = CertificateAuthority::whereNull('parent_id')->get();

        foreach ($roots as $root) {
            $rootCn = $root->subject_dn['CN'] ?? 'Unknown';
            $this->line("  [{$root->status}] {$rootCn} ({$root->id})");

            foreach ($root->children as $child) {
                $childCertCount = Certificate::where('ca_id', $child->id)
                    ->where('type', '!=', 'intermediate_ca')
                    ->count();
                $childCn = $child->subject_dn['CN'] ?? 'Unknown';
                $this->line("    [{$child->status}] {$childCn} ({$child->id}) - {$childCertCount} certs");
            }
        }

        return self::SUCCESS;
    }
}
