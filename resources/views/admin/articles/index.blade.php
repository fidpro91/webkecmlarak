@extends('layouts.admin')

@section('title', 'Artikel Berita')
@section('page_title', 'Manajemen Berita & Publikasi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Berita & Artikel</h2>
            <p class="text-xs text-slate-500">Kelola rilis berita, liputan kegiatan, dan artikel publikasi resmi</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Buat Berita Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="w-full sm:w-80 flex items-center relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel..." 
                   class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
        </form>

        <div class="flex items-center gap-2 text-xs text-slate-500">
            <span>Filter Kategori:</span>
            <select onchange="window.location.href = this.value" class="text-xs rounded-xl border-slate-200 focus:border-emerald-500 py-1.5 px-3">
                <option value="{{ route('admin.articles.index') }}">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ route('admin.articles.index', ['category_id' => $c->id]) }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Articles Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Gambar</th>
                        <th class="py-3.5 px-4">Judul Artikel</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Penulis</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4">Tanggal Publikasi</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($articles as $art)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <img src="{{ $art->gambar_url }}" alt="{{ $art->judul }}" class="w-20 h-12 rounded-xl object-cover shadow-sm">
                            </td>
                            <td class="py-3.5 px-4 max-w-xs">
                                <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="font-bold text-slate-900 text-sm hover:text-emerald-700 line-clamp-2">
                                    {{ $art->judul }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($art->category)
                                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-[10px]">
                                        {{ $art->category->nama }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-600">
                                {{ $art->author->name ?? 'Admin' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($art->status === 'published')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Tayang</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Draft</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $art->published_at ? $art->published_at->isoFormat('D MMM Y, HH:mm') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('articles.show', $art->slug) }}" target="_blank" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Lihat">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $art->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition" title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada artikel berita yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
