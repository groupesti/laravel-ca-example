@extends('layouts.app')

@section('title', $certificate->subject_dn['CN'] ?? 'Certificate')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Certificate Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-gray-800">Certificate Details</h3>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                        {{ $certificate->status === 'active' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $certificate->status === 'revoked' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $certificate->status === 'expired' ? 'bg-gray-100 text-gray-600' : '' }}">
                        {{ ucfirst($certificate->status ?? 'unknown') }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">UUID</dt>
                        <dd class="text-gray-900 font-mono text-xs mt-1">{{ $certificate->uuid }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Serial Number</dt>
                        <dd class="text-gray-900 font-mono text-xs mt-1">{{ $certificate->serial_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Type</dt>
                        <dd class="text-gray-900 mt-1">{{ str_replace('_', ' ', ucfirst($certificate->type ?? 'unknown')) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Issuer CA</dt>
                        <dd class="mt-1">
                            @if($certificate->certificateAuthority)
                                <a href="{{ route('cas.show', $certificate->certificateAuthority->id) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $certificate->certificateAuthority->subject_dn['CN'] ?? 'Unknown CA' }}
                                </a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Valid From</dt>
                        <dd class="text-gray-900 mt-1">{{ $certificate->not_before?->toDateTimeString() ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Valid Until</dt>
                        <dd class="text-gray-900 mt-1">{{ $certificate->not_after?->toDateTimeString() ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Fingerprint (SHA-256)</dt>
                        <dd class="text-gray-900 font-mono text-xs mt-1 break-all">{{ $certificate->fingerprint_sha256 ?? '-' }}</dd>
                    </div>
                    @if($certificate->revoked_at)
                        <div>
                            <dt class="text-gray-500">Revoked At</dt>
                            <dd class="text-red-600 mt-1">{{ $certificate->revoked_at->toDateTimeString() }}</dd>
                        </div>
                    @endif
                    @if($certificate->revocation_reason)
                        <div>
                            <dt class="text-gray-500">Revocation Reason</dt>
                            <dd class="text-red-600 mt-1">{{ str_replace('_', ' ', ucfirst($certificate->revocation_reason)) }}</dd>
                        </div>
                    @endif
                </dl>

                {{-- Subject DN --}}
                <h4 class="text-sm font-semibold text-gray-700 mt-6 mb-3">Subject Distinguished Name</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <dl class="space-y-2 text-sm">
                        @foreach($certificate->subject_dn ?? [] as $key => $value)
                            <div class="flex">
                                <dt class="w-20 font-mono text-gray-500">{{ $key }}</dt>
                                <dd class="text-gray-900">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- Extensions --}}
                @if($certificate->key_usage || $certificate->extended_key_usage || $certificate->san)
                    <h4 class="text-sm font-semibold text-gray-700 mt-6 mb-3">Extensions</h4>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3 text-sm">
                        @if($certificate->key_usage)
                            <div>
                                <dt class="text-gray-500 font-medium">Key Usage</dt>
                                <dd class="mt-1 flex flex-wrap gap-1">
                                    @foreach($certificate->key_usage as $usage)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $usage }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif

                        @if($certificate->extended_key_usage)
                            <div>
                                <dt class="text-gray-500 font-medium">Extended Key Usage</dt>
                                <dd class="mt-1 flex flex-wrap gap-1">
                                    @foreach($certificate->extended_key_usage as $eku)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-purple-50 text-purple-700 border border-purple-200">
                                            {{ $eku }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif

                        @if($certificate->san)
                            <div>
                                <dt class="text-gray-500 font-medium">Subject Alternative Names</dt>
                                <dd class="mt-1 flex flex-wrap gap-1">
                                    @foreach($certificate->san as $san)
                                        @if(is_array($san))
                                            @foreach($san as $type => $value)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-50 text-green-700 border border-green-200">
                                                    {{ $type }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-50 text-green-700 border border-green-200">
                                                {{ $san }}
                                            </span>
                                        @endif
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- PEM Display --}}
            @if($certificate->certificate_pem)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Certificate PEM</h3>
                    <pre class="bg-gray-900 text-green-400 text-xs p-4 rounded-lg overflow-x-auto font-mono leading-relaxed">{{ $certificate->certificate_pem }}</pre>
                </div>
            @endif
        </div>

        {{-- Actions Sidebar --}}
        <div class="space-y-6">
            {{-- Revoke --}}
            @if($certificate->status === 'active' && !in_array($certificate->type, ['root_ca', 'intermediate_ca']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Revoke Certificate</h3>
                    <form action="{{ route('demo.revoke') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="certificate_uuid" value="{{ $certificate->uuid }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                            <select name="reason" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="unspecified">Unspecified</option>
                                <option value="key_compromise">Key Compromise</option>
                                <option value="affiliation_changed">Affiliation Changed</option>
                                <option value="superseded">Superseded</option>
                                <option value="cessation_of_operation">Cessation of Operation</option>
                            </select>
                        </div>
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors"
                                onclick="return confirm('Are you sure you want to revoke this certificate?')">
                            Revoke Certificate
                        </button>
                    </form>
                </div>
            @endif

            {{-- Key Info --}}
            @if($certificate->key)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Key Information</h3>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-gray-500">Algorithm</dt>
                            <dd class="text-gray-900 mt-1">{{ strtoupper($certificate->key->algorithm ?? '-') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $certificate->key->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($certificate->key->status ?? 'unknown') }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Fingerprint</dt>
                            <dd class="text-gray-900 font-mono text-xs mt-1 break-all">{{ $certificate->key->fingerprint_sha256 ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            @endif

            {{-- Navigation --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4">Navigation</h3>
                <div class="space-y-2">
                    <a href="{{ route('certificates.index') }}" class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                        All Certificates
                    </a>
                    @if($certificate->certificateAuthority)
                        <a href="{{ route('cas.show', $certificate->certificateAuthority->id) }}" class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                            </svg>
                            Issuer CA
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
