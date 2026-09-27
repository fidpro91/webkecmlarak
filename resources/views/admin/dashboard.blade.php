@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Banner -->
    <div class="relative bg-gradient-to-r from-emerald-800 to-teal-900 rounded-3xl p-8 text-white shadow-xl overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-2">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-emerald-200 text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
                Selamat Datang
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
                Anda masuk sebagai <strong>{{ Auth::user()->isSuperAdmin() ? 'Super Administrator' : 'Admin Operator' }}</strong>. Kelola seluruh informasi publik, layanan perizinan terpadu, publikasi berita, dan pengaduan warga Kecamatan Mlarak melalui panel ini.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-60 h-60 bg-emerald-700/30 rounded-full blur-2xl"></div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- 1. Desa -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Data Desa</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['villages'] }}</p>
                <a href="{{ route('admin.villages.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline mt-2 inline-block">
                    Kelola Desa &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-tree-city"></i>
            </div>
        </div>

        <!-- 2. Penduduk -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Penduduk</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($stats['population'], 0, ',', '.') }}</p>
                <span class="text-[11px] text-slate-400 mt-2 block">15 Desa Terintegrasi</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- 3. Berita -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Artikel Berita</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['articles'] }}</p>
                <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline mt-2 inline-block">
                    Kelola Berita &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-newspaper"></i>
            </div>
        </div>

        <!-- 4. Pesan / Pengaduan -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pengaduan Belum Dibaca</span>
                <p class="text-3xl font-extrabold text-rose-600 mt-1">{{ $stats['messages_unread'] }}</p>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-rose-600 hover:underline mt-2 inline-block">
                    Buka Kotak Masuk &rarr;
                </a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-inbox"></i>
            </div>
        </div>
    </div>

    <!-- Tables Grid: Recent Messages & Articles -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Recent Grievances / Messages -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-lg">Pesan & Pengaduan Terbaru</h3>
                    <p class="text-xs text-slate-400">Aspirasi masuk dari formulir kontak website</p>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                    Semua Pesan
                </a>
            </div>

            @if($latestMessages->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($latestMessages as $msg)
                        <div class="py-3 flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-xs text-slate-800">{{ $msg->nama }}</span>
                                    <span class="text-[10px] text-slate-400">• {{ $msg->created_at->diffForHumans() }}</span>
                                    @if($msg->status === 'unread')
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-100 text-rose-700">Baru</span>
                                    @endif
                                </div>
                                <p class="text-xs font-semibold text-slate-700">{{ $msg->subjek }}</p>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $msg->pesan }}</p>
                            </div>
                            <a href="{{ route('admin.messages.show', $msg->id) }}" 
                               class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 text-xs font-semibold flex-shrink-0 transition">
                                Baca
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada pesan masuk.</p>
            @endif
        </div>

        <!-- Right: Recent Articles -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-lg">Artikel Berita Terbaru</h3>
                    <p class="text-xs text-slate-400">Postingan terbaru di portal</p>
                </div>
                <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                    Semua Berita
                </a>
            </div>

            @if($latestArticles->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($latestArticles as $art)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $art->gambar_url }}" alt="{{ $art->judul }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                                <div class="overflow-hidden">
                                    <h4 class="font-bold text-xs text-slate-800 line-clamp-1">{{ $art->judul }}</h4>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">{{ $art->created_at->isoFormat('D MMM Y') }}</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.articles.edit', $art->id) }}" class="text-slate-400 hover:text-emerald-600 text-xs p-1">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-slate-400 py-6 text-center">Belum ada artikel.</p>
            @endif
        </div>

    </div>

</div>
@endsection
