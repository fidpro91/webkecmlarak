@extends('layouts.frontend')

@section('title', $menu->nama_menu)

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Halaman</span>
                <span>/</span>
                <span class="text-white">{{ $menu->nama_menu }}</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $menu->nama_menu }}</h1>
            <p class="mt-2 text-sm text-rose-100/90">
                Pemerintah Kecamatan Mlarak, Kabupaten Ponorogo.
            </p>
        </div>
    </div>

    <!-- Main Dynamic Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
        @if($menu->page && $menu->page->gambar)
            <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200">
                <img src="{{ $menu->page->gambar_url }}" alt="{{ $menu->nama_menu }}" class="w-full max-h-[480px] object-cover">
            </div>
        @endif

        <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-sm">
            <article class="prose prose-slate lg:prose-lg max-w-none text-slate-700 leading-relaxed">
                {!! $menu->page->konten ?? '<p class="text-slate-500 italic">Konten halaman belum diisi.</p>' !!}
            </article>
        </div>
    </div>
@endsection
