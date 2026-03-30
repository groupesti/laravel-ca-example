@extends('layouts.app')

@section('title', $ca->subject_dn['CN'] ?? 'Certificate Authority')

@section('content')
    {{-- CA Details --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">CA Details</h3>

            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">UUID</dt>
                    <dd class="text-gray-900 font-mono text-xs mt-1">{{ $ca->id }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Type</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $ca->parent_id === null ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $ca->parent_id === null ? 'Root CA' : 'Intermediate CA' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $ca->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($ca->status) }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Serial Number</dt>
                    <dd class="text-gray-900 font-mono text-xs mt-1">{{ $ca->serial_number ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Key Algorithm</dt>
                    <dd class="text-gray-900 mt-1">{{ strtoupper($ca->key_algorithm ?? '-') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Hash Algorithm</dt>
                    <dd class="text-gray-900 mt-1">{{ strtoupper($ca->hash_algorithm ?? '-') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Valid From</dt>
                    <dd class="text-gray-900 mt-1">{{ $ca->not_before?->toDateTimeString() ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Valid Until</dt>
                    <dd class="text-gray-900 mt-1">{{ $ca->not_after?->toDateTimeString() ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Path Length</dt>
                    <dd class="text-gray-900 mt-1">{{ $ca->path_length ?? 'Unlimited' }}</dd>
                </div>
                @if($ca->parent)
                    <div>
                        <dt class="text-gray-500">Parent CA</dt>
                        <dd class="mt-1">
                            <a href="{{ route('cas.show', $ca->parent->id) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $ca->parent->subject_dn['CN'] ?? $ca->parent->id }}
                            </a>
                        </dd>
                    </div>
                @endif
            </dl>

            {{-- Subject DN --}}
            <h4 class="text-sm font-semibold text-gray-700 mt-6 mb-3">Subject Distinguished Name</h4>
            <div class="bg-gray-50 rounded-lg p-4">
                <dl class="space-y-2 text-sm">
                    @foreach($ca->subject_dn ?? [] as $key => $value)
                        <div class="flex">
                            <dt class="w-8 font-mono text-gray-500">{{ $key }}</dt>
                            <dd class="text-gray-900 ml-4">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-6">
            {{-- Issue Certificate --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Issue Certificate</h3>
                <form action="{{ route('demo.issue') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="ca_id" value="{{ $ca->id }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Common Name</label>
                        <input type="text" name="common_name" required placeholder="e.g. app.example.com"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="server_tls">Server TLS</option>
                            <option value="client_mtls">Client mTLS</option>
                            <option value="code_signing">Code Signing</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                        Issue Certificate
                    </button>
                </form>
            </div>

            {{-- Generate CRL --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Generate CRL</h3>
                <form action="{{ route('demo.crl') }}" method="POST">
                    @csrf
                    <input type="hidden" name="ca_id" value="{{ $ca->id }}">
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors">
                        Generate CRL
                    </button>
                </form>
            </div>

            {{-- Child CAs --}}
            @if($ca->children->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Child CAs</h3>
                    <ul class="space-y-2">
                        @foreach($ca->children as $child)
                            <li>
                                <a href="{{ route('cas.show', $child->id) }}"
                                   class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                    {{ $child->subject_dn['CN'] ?? $child->id }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- Certificates Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">Issued Certificates</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <a href="{{ route('certificates.show', $cert->uuid) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                                    {{ $cert->subject_dn['CN'] ?? 'Unknown' }}
                                </a>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">{{ $cert->type ?? '-' }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-xs text-gray-500 font-mono">{{ Str::limit($cert->serial_number, 16) }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $cert->status === 'active' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $cert->status === 'revoked' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $cert->status === 'expired' ? 'bg-gray-100 text-gray-600' : '' }}">
                                    {{ ucfirst($cert->status ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $cert->not_after?->toDateString() ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                No certificates issued by this CA.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
