<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use CA\Crt\Models\Certificate;
use CA\Models\CertificateAuthority;
use Illuminate\View\View;

final class CaExampleController extends Controller
{
    public function index(): View
    {
        $cas = CertificateAuthority::query()
            ->with('parent')
            ->withCount('children')
            ->orderByRaw('parent_id IS NOT NULL, created_at ASC')
            ->get();

        return view('cas.index', compact('cas'));
    }

    public function show(string $uuid): View
    {
        $ca = CertificateAuthority::query()
            ->with(['parent', 'children'])
            ->findOrFail($uuid);

        $certificates = Certificate::query()
            ->where('ca_id', $ca->id)
            ->orderByDesc('created_at')
            ->get();

        return view('cas.show', compact('ca', 'certificates'));
    }
}
