@extends('layouts.frontend')

@section('title', $village->nama)

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <a href="{{ route('villages.index') }}" class="hover:underline">Data Desa</a>
                <span>/</span>
                <span class="text-rose-200">{{ $village->nama }}</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $village->nama }}</h1>
            <p class="mt-2 text-sm text-rose-100/90">
                Pemerintah Desa {{ $village->nama }}, Kecamatan Mlarak, Kabupaten Ponorogo.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        
        <!-- Main Stats Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-medium block">Kepala Desa</span>
                    <p class="text-base font-extrabold text-slate-900">{{ $village->kepala_desa ?? 'Pemerintah Desa' }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-medium block">Jumlah Penduduk</span>
                    <p class="text-base font-extrabold text-slate-900">{{ number_format($village->jumlah_penduduk, 0, ',', '.') }} Jiwa</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-700 flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-vector-square"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-medium block">Luas Wilayah</span>
                    <p class="text-base font-extrabold text-slate-900">{{ $village->luas_wilayah ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Featured Photo & Description -->
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm space-y-6">
            @if($village->foto)
                <div class="rounded-2xl overflow-hidden shadow-md max-h-96">
                    <img src="{{ $village->foto_url }}" alt="{{ $village->nama }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="space-y-4">
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-emerald-600 rounded"></span> Gambaran Umum & Potensi {{ $village->nama }}
                </h3>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm sm:text-base space-y-4">
                    <p>{{ $village->deskripsi ?? 'Belum ada deskripsi lengkap untuk desa ini.' }}</p>
                </div>
            </div>
        </div>

        <!-- Other Villages -->
        @if($otherVillages->count() > 0)
            <div class="space-y-6">
                <h3 class="font-bold text-slate-900 text-xl flex items-center gap-2">
                    <span class="w-2.5 h-5 bg-emerald-600 rounded"></span> Desa Lainnya di Kecamatan Mlarak
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($otherVillages as $ov)
                        <a href="{{ route('villages.show', $ov->slug) }}" 
                           class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-emerald-500 hover:shadow transition block text-center">
                            <p class="font-bold text-slate-800 text-sm hover:text-emerald-700">{{ $ov->nama }}</p>
                            <span class="text-[11px] text-slate-400 mt-1 block">{{ number_format($ov->jumlah_penduduk, 0, ',', '.') }} Jiwa</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
