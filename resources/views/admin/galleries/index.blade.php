@extends('layouts.admin')

@section('title', 'Galeri')
@section('page_title', 'Manajemen Galeri Foto & Video')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Koleksi Media Galeri</h2>
            <p class="text-xs text-slate-500">Kelola foto dan tautan video dokumentasi kegiatan kecamatan</p>
        </div>
        <a href="{{ route('admin.galleries.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Media Baru
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('admin.galleries.index') }}" 
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ !request('tipe') ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}">
            Semua Media
        </a>
        <a href="{{ route('admin.galleries.index', ['tipe' => 'foto']) }}" 
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request('tipe') === 'foto' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}">
            <i class="fa-solid fa-image mr-1"></i> Foto Saja
        </a>
        <a href="{{ route('admin.galleries.index', ['tipe' => 'video']) }}" 
           class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request('tipe') === 'video' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200' }}">
            <i class="fa-solid fa-play mr-1"></i> Video Saja
        </a>
    </div>

    <!-- Galleries Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Preview</th>
                        <th class="py-3.5 px-4">Judul & Deskripsi</th>
                        <th class="py-3.5 px-4 text-center">Tipe Media</th>
                        <th class="py-3.5 px-4">Tanggal Diunggah</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($galleries as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                @if($item->tipe === 'foto')
                                    <img src="{{ $item->file_url }}" alt="{{ $item->judul }}" class="w-20 h-12 rounded-xl object-cover shadow-sm">
                                @else
                                    <div class="w-20 h-12 rounded-xl bg-slate-900 flex items-center justify-center text-rose-500 shadow-sm">
                                        <i class="fa-brands fa-youtube text-xl"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 max-w-sm">
                                <p class="font-bold text-slate-900 text-sm">{{ $item->judul }}</p>
                                <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $item->deskripsi ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $item->tipe === 'foto' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ strtoupper($item->tipe) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $item->created_at->isoFormat('D MMM Y, HH:mm') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.galleries.edit', $item->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.galleries.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item galeri ini?')">
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
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada item galeri.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $galleries->links() }}
        </div>
    </div>
</div>
@endsection
