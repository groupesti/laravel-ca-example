<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use CA\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AuditExampleController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::query()->orderByDesc('performed_at');

        if ($request->filled('action')) {
            $query->where('action', 'LIKE', '%' . $request->input('action') . '%');
        }

        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->input('subject_type'));
        }

        $auditLogs = $query->paginate(25);

        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $subjectTypes = AuditLog::query()
            ->select('subject_type')
            ->distinct()
            ->whereNotNull('subject_type')
            ->orderBy('subject_type')
            ->pluck('subject_type');

        return view('audit.index', compact('auditLogs', 'actions', 'subjectTypes'));
    }
}
