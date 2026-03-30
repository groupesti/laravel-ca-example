<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use CA\Crt\Models\Certificate;
use Illuminate\View\View;

final class CertificateExampleController extends Controller
{
    public function index(): View
    {
        $certificates = Certificate::query()
            ->with('certificateAuthority')
            ->orderByDesc('created_at')
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    public function show(string $uuid): View
    {
        $certificate = Certificate::query()
            ->with(['certificateAuthority', 'key', 'issuerCertificate'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('certificates.show', compact('certificate'));
    }
}
