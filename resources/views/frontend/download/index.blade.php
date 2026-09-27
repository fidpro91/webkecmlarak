@extends('layouts.frontend')

@section('title', 'Pusat Unduhan & Dokumen')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Download</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Transparansi Dokumen & Unduhan</h1>
            <p class="mt-2 text-sm text-rose-100/90 max-w-xl">
                Unduh formulir permohonan, SOP pelayanan, keputusan camat, dan laporan akuntabilitas kinerja instansi pemerintah.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left / Main: Downloads List -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Search & Status Bar -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form action="{{ route('downloads.index') }}" method="GET" class="w-full sm:w-80 flex items-center relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dokumen..." 
                               class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
                        @if(request('kategori'))
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                    </form>

                    <div class="text-xs text-slate-500">
                        @if(request('kategori'))
                            Kategori: <strong>{{ request('kategori') }}</strong>
                            <a href="{{ route('downloads.index') }}" class="text-rose-600 hover:underline font-semibold ml-2">Reset</a>
                        @else
                            Menampilkan {{ $downloads->total() }} berkas
                        @endif
                    </div>
                </div>

                <!-- Downloads Table / Cards -->
                @if($downloads->count() > 0)
                    <div class="space-y-4">
                        @foreach($downloads as $dl)
                            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl flex-shrink-0 font-bold uppercase {{ $dl->isPdf() ? 'bg-rose-50 text-rose-600' : 'bg-blue-50 text-blue-600' }}">
                                        @if($dl->isPdf())
                                            <i class="fa-solid fa-file-pdf"></i>
                                        @else
                                            <i class="fa-solid fa-file-word"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-base leading-snug">
                                            {{ $dl->judul }}
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400 mt-1 font-medium">
                                            @if($dl->category)
                                                <span class="text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full font-semibold">
                                                    {{ $dl->category->nama }}
                                                </span>
                                            @endif
                                            <span>Ukuran: <strong>{{ $dl->ukuran_file }}</strong></span>
                                            <span>•</span>
                                            <span>Diunduh: <strong>{{ $dl->jumlah_unduhan }}x</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 self-end sm:self-auto flex-shrink-0">
                                    @if($dl->isPdf())
                                        <!-- Tombol Lihat (Membuka Modal Iframe Preview PDF) -->
                                        <button onclick="openPreview('{{ route('downloads.preview', $dl->id) }}', '{{ addslashes($dl->judul) }}')" 
                                                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs flex items-center gap-1.5 transition">
                                            <i class="fa-solid fa-eye text-emerald-600"></i>
                                            <span>Lihat</span>
                                        </button>
                                    @endif

                                    <!-- Tombol Unduh (Download + Increment Counter) -->
                                    <a href="{{ route('downloads.download', $dl->id) }}" 
                                       class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs flex items-center gap-1.5 shadow-sm transition">
                                        <i class="fa-solid fa-download"></i>
                                        <span>Unduh</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        {{ $downloads->links() }}
                    </div>
                @else
                    <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3">
                        <i class="fa-solid fa-folder-open text-slate-300 text-5xl"></i>
                        <h3 class="font-bold text-slate-800 text-lg">Berkas Tidak Ditemukan</h3>
                        <p class="text-xs text-slate-500">Tidak ada dokumen yang sesuai dengan kata kunci pencarian.</p>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar: Categories Widget -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-base pb-3 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-folder-tree text-emerald-600"></i> Kategori Berkas
                    </h3>
                    <ul class="space-y-1.5 text-xs">
                        <li>
                            <a href="{{ route('downloads.index') }}" 
                               class="flex items-center justify-between p-2 rounded-xl transition {{ !request('kategori') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'hover:bg-slate-50 text-slate-600' }}">
                                <span>Semua Kategori</span>
                            </a>
                        </li>
                        @foreach($categories as $c)
                            <li>
                                <a href="{{ route('downloads.index', ['kategori' => $c->slug]) }}" 
                                   class="flex items-center justify-between p-2 rounded-xl transition {{ request('kategori') == $c->slug ? 'bg-emerald-50 text-emerald-700 font-bold' : 'hover:bg-slate-50 text-slate-600' }}">
                                    <span>{{ $c->nama }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('kategori') == $c->slug ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $c->downloads_count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection
