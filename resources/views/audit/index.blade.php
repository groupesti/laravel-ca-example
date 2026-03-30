@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form action="{{ route('audit.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject Type</label>
                <select name="subject_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    @foreach($subjectTypes as $type)
                        <option value="{{ $type }}" {{ request('subject_type') === $type ? 'selected' : '' }}>
                            {{ class_basename($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['action', 'subject_type']))
                    <a href="{{ route('audit.index') }}" class="ml-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Audit Log Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metadata</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($auditLogs as $entry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                <span title="{{ $entry->performed_at?->toDateTimeString() }}">
                                    {{ $entry->performed_at?->diffForHumans() ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $entry->action }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                {{ class_basename($entry->subject_type ?? 'Unknown') }}
                                @if($entry->subject_id)
                                    <span class="text-gray-400 font-mono text-xs block">{{ Str::limit($entry->subject_id, 12) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                @if($entry->actor_type)
                                    {{ class_basename($entry->actor_type) }}
                                    @if($entry->actor_id)
                                        <span class="text-gray-400 text-xs">{{ Str::limit($entry->actor_id, 8) }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">System</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500 font-mono">
                                {{ $entry->ip_address ?? '-' }}
                            </td>
                            <td class="px-6 py-3 text-sm">
                                @if($entry->metadata)
                                    <details class="cursor-pointer">
                                        <summary class="text-blue-600 hover:text-blue-800 text-xs">Show metadata</summary>
                                        <pre class="mt-2 bg-gray-50 p-2 rounded text-xs text-gray-600 max-w-xs overflow-x-auto">{{ json_encode($entry->metadata, JSON_PRETTY_PRINT) }}</pre>
                                    </details>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                No audit entries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($auditLogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $auditLogs->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
