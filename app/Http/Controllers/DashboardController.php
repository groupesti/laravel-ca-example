<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use CA\Crt\Models\Certificate;
use CA\Key\Models\Key;
use CA\Models\AuditLog;
use CA\Models\CertificateAuthority;
use CA\Models\CertificateStatus;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'cas' => CertificateAuthority::count(),
            'certificates' => Certificate::count(),
            'revoked' => Certificate::where('status', CertificateStatus::REVOKED)->count(),
            'keys' => Key::count(),
            'audit_entries' => AuditLog::count(),
        ];

        $recentAudit = AuditLog::query()
            ->orderByDesc('performed_at')
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'recentAudit'));
    }
}
