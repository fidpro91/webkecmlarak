@extends('layouts.frontend')

@section('title', $article->judul)
@section('meta_description', Str::limit(strip_tags($article->konten), 160))

@section('content')
    <!-- Article Header -->
    <div class="bg-[#5c0c16] py-14 text-white relative border-b border-[#7a1220]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
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
                    <span class="px-2.5 py-0.5 rounded-full bg-[#801422] text-white font-semibold text-[10px]">
                        {{ $article->category->nama }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Article Body & Related -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        
        <!-- Featured Image -->
        @if($article->gambar)
            <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200">
                <img src="{{ $article->gambar_url }}" alt="{{ $article->judul }}" class="w-full max-h-[500px] object-cover">
            </div>
        @endif

        <!-- Content -->
        <article class="prose prose-slate lg:prose-lg max-w-none text-slate-700 leading-relaxed space-y-4">
            {!! $article->konten !!}
        </article>

        <!-- Social Share Bar -->
        <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Bagikan Berita Ini:</span>
            <div class="flex items-center gap-2">
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
            </div>
        </div>

        <!-- Related News -->
        @if($relatedArticles->count() > 0)
            <div class="pt-8 border-t border-slate-200 space-y-6">
                <h3 class="font-bold text-slate-900 text-xl flex items-center gap-2">
                    <span class="w-2.5 h-5 bg-emerald-600 rounded"></span> Berita Terkait Lainnya
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $rel)
                        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-lg transition group">
                            <img src="{{ $rel->gambar_url }}" alt="{{ $rel->judul }}" class="w-full h-36 object-cover group-hover:scale-105 transition">
                            <div class="p-4">
                                <span class="text-[10px] text-slate-400 block mb-1">{{ $rel->published_at ? $rel->published_at->isoFormat('D MMM Y') : '' }}</span>
                                <h4 class="font-bold text-slate-900 text-sm line-clamp-2 group-hover:text-emerald-700 transition">
                                    <a href="{{ route('articles.show', $rel->slug) }}">{{ $rel->judul }}</a>
                                </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
