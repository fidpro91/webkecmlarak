@extends('layouts.admin')

@section('title', 'Slider Beranda')
@section('page_title', 'Manajemen Slider Beranda')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Slider Carousel</h2>
            <p class="text-xs text-slate-500">Gambar dan teks yang tampil bergantian di header halaman utama portal</p>
        </div>
        <a href="{{ route('admin.sliders.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Slider Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4 text-center">Urutan</th>
                        <th class="py-3.5 px-4">Gambar</th>
                        <th class="py-3.5 px-4">Judul & Deskripsi</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sliders as $slider)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700">{{ $slider->urutan }}</td>
                            <td class="py-3.5 px-4">
                                <img src="{{ $slider->gambar_url }}" alt="{{ $slider->judul }}" class="w-24 h-14 rounded-xl object-cover shadow-sm">
                            </td>
                            <td class="py-3.5 px-4 max-w-md">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-bold text-slate-900 text-sm">{{ $slider->judul }}</p>
                                    @if(($slider->layout_style ?? 'classic') === 'split_diagonal')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="fa-solid fa-shapes text-[9px]"></i> Split Miring
                                        </span>
                                    @elseif(($slider->layout_style ?? 'classic') === 'gradient_soft')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <i class="fa-solid fa-sliders text-[9px]"></i> Gradien Lembut
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            <i class="fa-regular fa-image text-[9px]"></i> Klasik
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ $slider->deskripsi ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($slider->status)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slider ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada slider yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
