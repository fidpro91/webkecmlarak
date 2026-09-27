@extends('layouts.frontend')

@section('title', 'Data 15 Desa')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Data Desa</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Profil & Potensi 15 Desa</h1>
            <p class="mt-2 text-sm text-rose-100/90 max-w-xl">
                Kecamatan Mlarak menaungi 15 desa dengan potensi agraris, pendidikan pesantren, UMKM, dan kearifan lokal yang beragam.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        
        <!-- Summary Strip & Search Bar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Total Desa</span>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $totalVillages }} Desa</p>
                </div>
                <div class="h-8 w-px bg-slate-200"></div>
                <div>
                    <span class="text-xs text-slate-500 font-medium block">Total Penduduk</span>
                    <p class="text-2xl font-extrabold text-emerald-700">{{ number_format($totalPopulation, 0, ',', '.') }} Jiwa</p>
                </div>
            </div>

            <form action="{{ route('villages.index') }}" method="GET" class="w-full md:w-80 flex items-center relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama desa / kepala desa..." 
                       class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
            </form>
        </div>

        <!-- Village Grid Cards -->
        @if($villages->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($villages as $v)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                        <div class="relative h-44 bg-slate-100 overflow-hidden">
                            <img src="{{ $v->foto_url }}" alt="{{ $v->nama }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <span class="absolute top-3 right-3 px-3 py-1 rounded-full bg-slate-900/80 text-white text-[11px] font-bold backdrop-blur-sm shadow">
                                <i class="fa-solid fa-users text-emerald-400 mr-1"></i> {{ number_format($v->jumlah_penduduk, 0, ',', '.') }} Jiwa
                            </span>
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-slate-900 text-xl group-hover:text-emerald-700 transition">
                                    <a href="{{ route('villages.show', $v->slug) }}">{{ $v->nama }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                                    <i class="fa-solid fa-user-tie text-emerald-600"></i> Kades: <strong>{{ $v->kepala_desa ?? 'Pemerintah Desa' }}</strong>
                                </p>
                                @if($v->luas_wilayah)
                                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-vector-square text-emerald-600"></i> Luas: {{ $v->luas_wilayah }}
                                    </p>
                                @endif
                                <p class="text-xs text-slate-600 mt-3 line-clamp-3 leading-relaxed">
                                    {{ Str::limit(strip_tags($v->deskripsi), 130) }}
                                </p>
                            </div>
                            <div class="pt-5 mt-4 border-t border-slate-100 flex items-center justify-between">
                                <a href="{{ route('villages.show', $v->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 group-hover:text-emerald-800 transition">
                                    <span>Lihat Profil Desa</span> <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-6">
                {{ $villages->links() }}
            </div>
        @else
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3">
                <i class="fa-solid fa-tree-city text-slate-300 text-5xl"></i>
                <h3 class="font-bold text-slate-800 text-lg">Desa Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500">Silakan gunakan kata kunci pencarian yang lain.</p>
                <a href="{{ route('villages.index') }}" class="inline-block text-xs font-semibold text-emerald-600 hover:underline">
                    Tampilkan Semua Desa
                </a>
            </div>
        @endif

    </div>
@endsection
