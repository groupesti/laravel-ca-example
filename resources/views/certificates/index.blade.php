@extends('layouts.app')

@section('title', 'Certificates')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">All Certificates</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issuer CA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('certificates.show', $cert->uuid) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                    {{ $cert->subject_dn['CN'] ?? 'Unknown' }}
                                </a>
                                @if($cert->subject_dn['O'] ?? null)
                                    <p class="text-xs text-gray-400">{{ $cert->subject_dn['O'] }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($cert->certificateAuthority)
                                    <a href="{{ route('cas.show', $cert->certificateAuthority->id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $cert->certificateAuthority->subject_dn['CN'] ?? 'Unknown CA' }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ str_replace('_', ' ', ucfirst($cert->type ?? 'unknown')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">
                                {{ Str::limit($cert->serial_number, 16) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $cert->status === 'active' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $cert->status === 'revoked' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $cert->status === 'expired' ? 'bg-gray-100 text-gray-600' : '' }}">
                                    {{ ucfirst($cert->status ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $cert->not_after?->toDateString() ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                No certificates found. Run <code class="bg-gray-100 px-2 py-1 rounded">php artisan ca-example:setup</code> to create demo data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
