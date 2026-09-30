@extends('layouts.frontend')

@section('title', 'Berita & Informasi')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Berita</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Kabar & Informasi Terkini</h1>
            <p class="mt-2 text-sm text-rose-100/90 max-w-xl">
                Publikasi transparansi, agenda kegiatan, pengumuman resmi, dan perkembangan desa se-Kecamatan Mlarak.
            </p>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left / Main: Articles Grid -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Search & Active Filter Bar -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form action="{{ route('articles.index') }}" method="GET" class="w-full sm:w-80 flex items-center relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul berita..." 
                               class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
                        @if(request('kategori'))
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        @endif
                    </form>

                    <div class="flex items-center gap-2 text-xs text-slate-500 self-start sm:self-auto">
                        @if(request('kategori') || request('q') || !empty($activeTag))
                            <span>Filter aktif: 
                                <strong class="text-slate-800">{{ $activeTag ?: (request('kategori') ?: request('q')) }}</strong>
                            </span>
                            <a href="{{ route('articles.index') }}" class="text-rose-600 hover:underline font-semibold ml-2 inline-flex items-center gap-1">
                                <i class="fa-solid fa-xmark text-[10px]"></i> Reset Filter
                            </a>
                        @else
                            <span>Menampilkan {{ $articles->total() }} berita</span>
                        @endif
                    </div>
                </div>

                @if($articles->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($articles as $article)
                            <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                                <div class="relative h-48 overflow-hidden bg-slate-100">
                                    <img src="{{ $article->gambar_url }}" 
                                         alt="{{ $article->judul }}" 
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @if($article->category)
                                        <a href="{{ route('articles.index', ['kategori' => $article->category->slug]) }}" 
                                           class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full bg-emerald-600/90 hover:bg-emerald-700 text-white text-[10px] font-bold tracking-wide shadow">
                                            {{ $article->category->nama }}
                                        </a>
                                    @endif
                                </div>
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-400 mb-2 font-medium">
                                            <span><i class="fa-regular fa-calendar text-emerald-600 mr-1"></i> {{ $article->published_at ? $article->published_at->isoFormat('D MMM Y') : $article->created_at->isoFormat('D MMM Y') }}</span>
                                            <span>•</span>
                                            <span><i class="fa-regular fa-user text-emerald-600 mr-1"></i> {{ $article->author->name ?? 'Admin' }}</span>
                                        </div>
                                        <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-emerald-700 transition line-clamp-2">
                                            <a href="{{ route('articles.show', $article->slug) }}">
                                                {{ $article->judul }}
                                            </a>
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                            {{ Str::limit(strip_tags($article->konten), 110) }}
                                        </p>
                                    </div>
                                    <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                                        <div class="flex flex-wrap items-center gap-1 overflow-hidden">
                                            @foreach(array_slice($article->formatted_hashtags, 0, 2) as $tag)
                                                <span class="inline-block text-[10px] font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <a href="{{ route('articles.show', $article->slug) }}" 
                                           class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 group-hover:text-emerald-800 transition flex-shrink-0">
                                            <span>Baca Detail</span>
                                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-6">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center space-y-3">
                        <i class="fa-solid fa-newspaper text-slate-300 text-5xl"></i>
                        <h3 class="font-bold text-slate-800 text-lg">Belum Ada Berita</h3>
                        <p class="text-xs text-slate-500">Tidak ada berita yang cocok dengan kata kunci pencarian atau kategori ini.</p>
                        <a href="{{ route('articles.index') }}" class="inline-block text-xs font-semibold text-emerald-600 hover:underline">
                            Tampilkan Semua Berita
                        </a>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar: Categories & Recent -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Categories Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-base pb-3 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-emerald-600"></i> Kategori Berita
                    </h3>
                    <ul class="space-y-1.5 text-xs">
                        <li>
                            <a href="{{ route('articles.index') }}" 
                               class="flex items-center justify-between p-2 rounded-xl transition {{ !request('kategori') ? 'bg-emerald-50 text-emerald-700 font-bold' : 'hover:bg-slate-50 text-slate-600' }}">
                                <span>Semua Kategori</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('articles.index', ['kategori' => $cat->slug]) }}" 
                                   class="flex items-center justify-between p-2 rounded-xl transition {{ request('kategori') == $cat->slug ? 'bg-emerald-50 text-emerald-700 font-bold' : 'hover:bg-slate-50 text-slate-600' }}">
                                    <span>{{ $cat->nama }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('kategori') == $cat->slug ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $cat->articles_count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Recent News Widget -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-base pb-3 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-500"></i> Berita Terpopuler
                    </h3>
                    <div class="space-y-3">
                        @foreach($recentArticles as $recent)
                            <a href="{{ route('articles.show', $recent->slug) }}" class="flex gap-3 group">
                                <img src="{{ $recent->gambar_url }}" alt="{{ $recent->judul }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 line-clamp-2 leading-snug">
                                        {{ $recent->judul }}
                                    </h4>
                                    <p class="text-[10px] text-slate-400 mt-1">
                                        {{ $recent->published_at ? $recent->published_at->isoFormat('D MMM Y') : $recent->created_at->isoFormat('D MMM Y') }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
