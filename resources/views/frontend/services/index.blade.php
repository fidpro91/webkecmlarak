@extends('layouts.frontend')

@section('title', 'Layanan Publik PATEN')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Layanan</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Pelayanan Administrasi Terpadu (PATEN)</h1>
            <p class="mt-2 text-sm text-rose-100/90 max-w-2xl">
                Standar operasional prosedur, syarat, dan tata cara pengurusan perizinan serta administrasi kependudukan di Kantor Kecamatan Mlarak.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        
        <!-- Search Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('services.index') }}" method="GET" class="w-full sm:w-96 flex items-center relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama layanan / kata kunci..." 
                       class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
            </form>
            <div class="text-xs text-slate-500">
                Tersedia <strong>{{ $services->count() }} jenis layanan</strong> publik
            </div>
        </div>

        <!-- Services Accordion List -->
        <div class="space-y-4" x-data="{ activeIndex: null }">
            @forelse($services as $index => $srv)
                <div id="srv-{{ $srv->id }}" 
                     class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm transition hover:border-emerald-500/50">
                    <!-- Accordion Trigger Button -->
                    <button @click="activeIndex = (activeIndex === {{ $index }}) ? null : {{ $index }}" 
                            class="w-full p-6 text-left flex items-center justify-between gap-4 focus:outline-none select-none">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0 transition-colors"
                                 :class="activeIndex === {{ $index }} ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700'">
                                <i class="fa-solid fa-file-contract"></i>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg leading-snug">
                                    {{ $srv->nama_layanan }}
                                </h3>
                                <p class="text-xs text-slate-400 mt-0.5">Klik untuk melihat kelengkapan syarat & prosedur permohonan</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0 transition-transform duration-300"
                             :class="activeIndex === {{ $index }} ? 'rotate-180 bg-emerald-100 text-emerald-700' : ''">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </button>

                    <!-- Accordion Body -->
                    <div x-show="activeIndex === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-[1000px]"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 max-h-[1000px]"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="px-6 pb-6 pt-2 border-t border-slate-100 space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <!-- Syarat -->
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                                <h4 class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2 text-emerald-800">
                                    <i class="fa-solid fa-clipboard-check text-emerald-600"></i> Persyaratan Administrasi
                                </h4>
                                <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line space-y-1">
                                    {{ $srv->syarat }}
                                </div>
                            </div>

                            <!-- Prosedur -->
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/80">
                                <h4 class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2 text-emerald-800">
                                    <i class="fa-solid fa-diagram-project text-emerald-600"></i> Prosedur & Alur Layanan
                                </h4>
                                <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line space-y-1">
                                    {{ $srv->prosedur }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar (SOP Download & Chat) -->
                        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                @if($srv->file_sop)
                                    <button onclick="openPreview('{{ route('downloads.preview', 1) }}', 'SOP {{ $srv->nama_layanan }}')"
                                            class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold flex items-center gap-2 transition">
                                        <i class="fa-solid fa-file-pdf text-rose-400"></i> Pratinjau SOP Resmi
                                    </button>
                                @endif
                            </div>
                            <a href="{{ route('contact.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline flex items-center gap-1">
                                Butuh Bantuan / Konsultasi Petugas? <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3">
                    <i class="fa-solid fa-hand-holding-heart text-slate-300 text-5xl"></i>
                    <h3 class="font-bold text-slate-800 text-lg">Layanan Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500">Silakan gunakan kata kunci pencarian yang lain.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
