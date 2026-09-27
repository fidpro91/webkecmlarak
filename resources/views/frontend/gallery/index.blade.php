@extends('layouts.frontend')

@section('title', 'Galeri Kegiatan')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Galeri</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Dokumentasi & Galeri Kegiatan</h1>
            <p class="mt-2 text-sm text-rose-100/90 max-w-xl">
                Koleksi foto dan video rekam jejak pembangunan, pelayanan masyarakat, dan pentas seni budaya di Kecamatan Mlarak.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-200 pb-4">
            <a href="{{ route('galleries.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ !request('tipe') ? 'bg-emerald-600 text-white shadow' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}">
                Semua Media
            </a>
            <a href="{{ route('galleries.index', ['tipe' => 'foto']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('tipe') === 'foto' ? 'bg-emerald-600 text-white shadow' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}">
                <i class="fa-regular fa-image mr-1"></i> Foto
            </a>
            <a href="{{ route('galleries.index', ['tipe' => 'video']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ request('tipe') === 'video' ? 'bg-emerald-600 text-white shadow' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}">
                <i class="fa-solid fa-play mr-1"></i> Video
            </a>
        </div>

        <!-- Gallery Grid -->
        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($galleries as $item)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group cursor-pointer"
                         @if($item->tipe === 'foto')
                             onclick="openLightbox('{{ $item->file_url }}', '{{ addslashes($item->judul) }}')"
                         @else
                             onclick="window.open('{{ $item->file }}', '_blank')"
                         @endif>
                        <div class="relative h-52 bg-slate-100 overflow-hidden">
                            @if($item->tipe === 'foto')
                                <img src="{{ $item->file_url }}" alt="{{ $item->judul }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <span class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-lg">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </span>
                                </div>
                            @else
                                <div class="w-full h-full bg-slate-950 flex flex-col items-center justify-center text-white p-4">
                                    <span class="w-14 h-14 rounded-full bg-rose-600 text-white flex items-center justify-center text-2xl shadow-lg group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-play ml-1"></i>
                                    </span>
                                    <span class="text-xs text-slate-400 mt-3">Tonton di YouTube</span>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide shadow {{ $item->tipe === 'foto' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                <i class="fa-solid {{ $item->tipe === 'foto' ? 'fa-image' : 'fa-video' }} mr-1"></i> {{ ucfirst($item->tipe) }}
                            </span>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <h3 class="font-bold text-slate-900 text-sm line-clamp-2 group-hover:text-emerald-700 transition">
                                {{ $item->judul }}
                            </h3>
                            @if($item->deskripsi)
                                <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">
                                    {{ $item->deskripsi }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-6">
                {{ $galleries->links() }}
            </div>
        @else
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3">
                <i class="fa-solid fa-photo-film text-slate-300 text-5xl"></i>
                <h3 class="font-bold text-slate-800 text-lg">Media Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500">Belum ada item galeri pada kategori ini.</p>
            </div>
        @endif

    </div>
@endsection
