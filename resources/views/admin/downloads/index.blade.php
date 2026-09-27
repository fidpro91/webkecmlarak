@extends('layouts.admin')

@section('title', 'Berkas Unduhan')
@section('page_title', 'Manajemen Berkas Unduhan & Transparansi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Dokumen Publik</h2>
            <p class="text-xs text-slate-500">Kelola berkas formulir permohonan, regulasi SK Camat, dan laporan berkala</p>
        </div>
        <a href="{{ route('admin.downloads.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-cloud-arrow-up"></i> Unggah Berkas Baru
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.downloads.index') }}" method="GET" class="w-full sm:w-80 flex items-center relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama dokumen..." 
                   class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
        </form>

        <div class="flex items-center gap-2 text-xs text-slate-500">
            <span>Filter Kategori:</span>
            <select onchange="window.location.href = this.value" class="text-xs rounded-xl border-slate-200 focus:border-emerald-500 py-1.5 px-3">
                <option value="{{ route('admin.downloads.index') }}">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ route('admin.downloads.index', ['category_id' => $c->id]) }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Downloads Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Tipe</th>
                        <th class="py-3.5 px-4">Judul Dokumen</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Ukuran</th>
                        <th class="py-3.5 px-4 text-center">Diunduh</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($downloads as $dl)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-mono font-bold uppercase">
                                <span class="px-2 py-1 rounded-lg {{ $dl->isPdf() ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $dl->tipe_file }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm max-w-xs">
                                {{ $dl->judul }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-medium">
                                    {{ $dl->category->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 font-mono">{{ $dl->ukuran_file }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-700">{{ $dl->jumlah_unduhan }}x</td>
                            <td class="py-3.5 px-4 text-center">
                                @if($dl->status)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    @if($dl->isPdf())
                                        <a href="{{ route('downloads.preview', $dl->id) }}" target="_blank" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Preview PDF">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('downloads.download', $dl->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Unduh">
                                        <i class="fa-solid fa-download text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.downloads.edit', $dl->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.downloads.destroy', $dl->id) }}" method="POST" onsubmit="return confirm('Hapus berkas ini?')">
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
                            <td colspan="7" class="py-8 text-center text-slate-400">Belum ada berkas unduhan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $downloads->links() }}
        </div>
    </div>
</div>
@endsection
