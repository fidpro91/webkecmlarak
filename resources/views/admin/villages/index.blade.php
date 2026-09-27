@extends('layouts.admin')

@section('title', 'Data Desa')
@section('page_title', 'Manajemen Data 15 Desa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Desa se-Kecamatan Mlarak</h2>
            <p class="text-xs text-slate-500">Kelola profil desa, nama kepala desa, data statistik penduduk, dan potensi wilayah</p>
        </div>
        <a href="{{ route('admin.villages.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Desa Baru
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <form action="{{ route('admin.villages.index') }}" method="GET" class="w-full sm:w-80 flex items-center relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama desa / kades..." 
                   class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
        </form>
    </div>

    <!-- Villages Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Foto</th>
                        <th class="py-3.5 px-4">Nama Desa</th>
                        <th class="py-3.5 px-4">Kepala Desa</th>
                        <th class="py-3.5 px-4">Luas Wilayah</th>
                        <th class="py-3.5 px-4 text-right">Jumlah Penduduk</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($villages as $v)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4">
                                <img src="{{ $v->foto_url }}" alt="{{ $v->nama }}" class="w-16 h-10 rounded-xl object-cover shadow-sm">
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                <a href="{{ route('villages.show', $v->slug) }}" target="_blank" class="hover:text-emerald-700">
                                    {{ $v->nama }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-slate-700">
                                {{ $v->kepala_desa ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $v->luas_wilayah ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-extrabold text-emerald-700">
                                {{ number_format($v->jumlah_penduduk, 0, ',', '.') }} Jiwa
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('villages.show', $v->slug) }}" target="_blank" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Lihat">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.villages.edit', $v->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.villages.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Hapus data desa ini?')">
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
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada data desa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $villages->links() }}
        </div>
    </div>
</div>
@endsection
