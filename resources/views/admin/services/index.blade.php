@extends('layouts.admin')

@section('title', 'Layanan Publik PATEN')
@section('page_title', 'Manajemen Layanan Publik (PATEN)')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Layanan Administrasi Terpadu</h2>
            <p class="text-xs text-slate-500">Kelola standar persyaratan, alur prosedur, dan berkas SOP layanan kecamatan</p>
        </div>
        <a href="{{ route('admin.services.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Layanan Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4 text-center">No</th>
                        <th class="py-3.5 px-4">Nama Layanan</th>
                        <th class="py-3.5 px-4">Persyaratan</th>
                        <th class="py-3.5 px-4">Prosedur</th>
                        <th class="py-3.5 px-4 text-center">Berkas SOP</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($services as $index => $srv)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm max-w-xs">
                                {{ $srv->nama_layanan }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs">
                                <p class="line-clamp-2">{{ Str::limit(strip_tags($srv->syarat), 90) }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs">
                                <p class="line-clamp-2">{{ Str::limit(strip_tags($srv->prosedur), 90) }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($srv->file_sop)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-file-pdf text-rose-500"></i> Ada SOP
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[10px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.services.edit', $srv->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $srv->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
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
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada layanan publik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $services->links() }}
        </div>
    </div>
</div>
@endsection
