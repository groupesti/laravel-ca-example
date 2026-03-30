@extends('layouts.app')

@section('title', 'Certificate Authorities')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($cas as $ca)
            <a href="{{ route('cas.show', $ca->id) }}" class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            @if($ca->parent_id === null)
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $ca->subject_dn['CN'] ?? 'Unknown' }}</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $ca->parent_id === null ? 'Root CA' : 'Intermediate CA' }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $ca->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($ca->status) }}
                        </span>
                    </div>

                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Organization</dt>
                            <dd class="text-gray-700">{{ $ca->subject_dn['O'] ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Algorithm</dt>
                            <dd class="text-gray-700">{{ strtoupper($ca->key_algorithm ?? '-') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Valid Until</dt>
                            <dd class="text-gray-700">{{ $ca->not_after?->toDateString() ?? '-' }}</dd>
                        </div>
                        @if($ca->parent)
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Parent</dt>
                                <dd class="text-gray-700 truncate max-w-[150px]">{{ $ca->parent->subject_dn['CN'] ?? '-' }}</dd>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Sub CAs</dt>
                            <dd class="text-gray-700">{{ $ca->children_count }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="px-6 py-3 bg-gray-50 rounded-b-xl border-t border-gray-100">
                    <p class="text-xs text-gray-400 font-mono truncate">{{ $ca->id }}</p>
                </div>
            </a>
        @empty
            <div class="col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <p class="text-gray-500">No certificate authorities found.</p>
                <p class="text-sm text-gray-400 mt-2">Run <code class="bg-gray-100 px-2 py-1 rounded">php artisan ca-example:setup</code> to create demo CAs.</p>
            </div>
        @endforelse
    </div>
@endsection
