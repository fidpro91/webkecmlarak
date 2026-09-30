@extends('layouts.frontend')

@section('title', $article->judul)
@section('meta_description', Str::limit(strip_tags($article->konten), 160))

@php
    $tagList = array_map(fn($t) => ltrim($t, '#'), $article->formatted_hashtags ?? []);
    $keywordsArray = array_merge(
        $tagList,
        [$article->category->nama ?? 'Berita', 'Kecamatan Mlarak', 'Kabupaten Ponorogo', 'Jawa Timur']
    );
    $metaKeywords = implode(', ', array_unique($keywordsArray));
@endphp

@section('meta_keywords', $metaKeywords)

@section('seo_meta')
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $article->judul }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($article->konten), 180) }}">
    <meta property="og:image" content="{{ $article->gambar_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="article:published_time" content="{{ ($article->published_at ?? $article->created_at)->toIso8601String() }}">
    @if($article->category)
        <meta property="article:section" content="{{ $article->category->nama }}">
    @endif
    @foreach($tagList as $tag)
        <meta property="article:tag" content="{{ $tag }}">
    @endforeach

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->judul }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($article->konten), 180) }}">
    <meta name="twitter:image" content="{{ $article->gambar_url }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": @json($article->judul),
        "image": [@json($article->gambar_url)],
        "datePublished": @json(($article->published_at ?? $article->created_at)->toIso8601String()),
        "dateModified": @json($article->updated_at->toIso8601String()),
        "author": {
            "@type": "Person",
            "name": @json($article->author->name ?? 'Admin Kecamatan Mlarak')
        },
        "publisher": {
            "@type": "GovernmentOrganization",
            "name": "Pemerintah Kecamatan Mlarak",
            "logo": {
                "@type": "ImageObject",
                "url": @json(\App\Models\Setting::logoUrl())
            }
        },
        "description": @json(Str::limit(strip_tags($article->konten), 180)),
        "keywords": @json($metaKeywords)
    }
    </script>
@endsection

@section('content')
    <!-- Article Header -->
    <div class="bg-[#5c0c16] py-14 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-3 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <a href="{{ route('articles.index') }}" class="hover:underline">Berita</a>
                <span>/</span>
                <span class="text-rose-200">Detail</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                {{ $article->judul }}
            </h1>
            <div class="mt-4 flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-rose-100/90">
                <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar text-amber-300"></i> {{ $article->published_at ? $article->published_at->isoFormat('dddd, D MMMM Y') : $article->created_at->isoFormat('dddd, D MMMM Y') }}</span>
                <span>•</span>
                <span class="flex items-center gap-1.5"><i class="fa-regular fa-user text-amber-300"></i> {{ $article->author->name ?? 'Admin Kecamatan' }}</span>
                @if($article->category)
                    <span>•</span>
                    <a href="{{ route('articles.index', ['kategori' => $article->category->slug]) }}" class="px-2.5 py-0.5 rounded-full bg-[#801422] hover:bg-rose-900 text-white font-semibold text-[10px] transition">
                        {{ $article->category->nama }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Area with Right Sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left / Main: Article Content & Related -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Featured Image -->
                @if($article->gambar)
                    <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200">
                        <img src="{{ $article->gambar_url }}" alt="{{ $article->judul }}" class="w-full max-h-[500px] object-cover">
                    </div>
                @endif

                <!-- Article Text Content -->
                <article class="prose prose-slate lg:prose-lg max-w-none text-slate-700 leading-relaxed space-y-4 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm">
                    {!! $article->konten !!}
                </article>

                <!-- Hashtags / Tagar Berita (SEO & Discovery) -->
                @if(!empty($article->formatted_hashtags))
                    <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-2 h-4 bg-rose-600 rounded"></span>
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Topik Terkait & Tagar:</h4>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach($article->formatted_hashtags as $tag)
                                <a href="{{ route('articles.index', ['tag' => ltrim($tag, '#')]) }}" 
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-900 border border-rose-200/80 transition shadow-xs group">
                                    <i class="fa-solid fa-hashtag text-[11px] text-rose-500 group-hover:rotate-12 transition transform"></i>
                                    <span>{{ ltrim($tag, '#') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Social Share Bar -->
                <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Bagikan Berita Ini:</span>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($article->judul . ' ' . url()->current()) }}" target="_blank" 
                           class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold flex items-center gap-2 transition">
                            <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" 
                           class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold flex items-center gap-2 transition">
                            <i class="fa-brands fa-facebook-f text-sm"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->judul) }}&url={{ urlencode(url()->current()) }}" target="_blank" 
                           class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-black text-white text-xs font-semibold flex items-center gap-2 transition">
                            <i class="fa-brands fa-x-twitter text-sm"></i> X (Twitter)
                        </a>
                        <a href="{{ $siteSettings['instagram'] ?? 'https://www.instagram.com/' }}" target="_blank" 
                           class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 via-pink-600 to-amber-500 hover:opacity-90 text-white text-xs font-semibold flex items-center gap-2 transition shadow-xs">
                            <i class="fa-brands fa-instagram text-sm"></i> Instagram
                        </a>
                        <button type="button" 
                                onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan berita berhasil disalin ke clipboard!');" 
                                title="Salin Tautan Berita"
                                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition border border-slate-200">
                            <i class="fa-regular fa-copy text-sm"></i> Salin Link
                        </button>
                    </div>
                </div>

                <!-- Related News (Berita Terkait Lainnya) -->
                @if($relatedArticles->count() > 0)
                    <div class="p-6 sm:p-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
                        <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                            <span class="w-2.5 h-5 bg-emerald-600 rounded"></span> Berita Terkait Lainnya
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            @foreach($relatedArticles as $rel)
                                <div class="bg-slate-50/70 rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-md transition group flex flex-col justify-between">
                                    <div>
                                        <div class="relative h-32 overflow-hidden bg-slate-100">
                                            <img src="{{ $rel->gambar_url }}" alt="{{ $rel->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        </div>
                                        <div class="p-3.5">
                                            <span class="text-[10px] text-slate-400 block mb-1">
                                                <i class="fa-regular fa-calendar mr-1"></i>{{ $rel->published_at ? $rel->published_at->isoFormat('D MMM Y') : '' }}
                                            </span>
                                            <h4 class="font-bold text-slate-900 text-xs line-clamp-2 leading-snug group-hover:text-emerald-700 transition">
                                                <a href="{{ route('articles.show', $rel->slug) }}">{{ $rel->judul }}</a>
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="px-3.5 pb-3 pt-0">
                                        <a href="{{ route('articles.show', $rel->slug) }}" class="text-[11px] font-bold text-emerald-700 hover:underline inline-flex items-center gap-1">
                                            <span>Baca Detail</span> <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Sidebar: Categories & Recent / Popular News -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Search Box Widget -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <form action="{{ route('articles.index') }}" method="GET" class="relative">
                        <input type="text" name="q" placeholder="Cari berita lain..." 
                               class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                    </form>
                </div>

                <!-- Categories Card (Kategori Berita) -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-base pb-3 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-emerald-600"></i> Kategori Berita
                    </h3>
                    <ul class="space-y-1.5 text-xs">
                        <li>
                            <a href="{{ route('articles.index') }}" 
                               class="flex items-center justify-between p-2 rounded-xl transition hover:bg-slate-50 text-slate-600">
                                <span>Semua Kategori</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('articles.index', ['kategori' => $cat->slug]) }}" 
                                   class="flex items-center justify-between p-2 rounded-xl transition {{ $article->category_id == $cat->id ? 'bg-emerald-50 text-emerald-700 font-bold' : 'hover:bg-slate-50 text-slate-600' }}">
                                    <span>{{ $cat->nama }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $article->category_id == $cat->id ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $cat->articles_count }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Recent / Popular News Widget (Berita Populer) -->
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
