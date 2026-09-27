@extends('layouts.admin')

@section('title', 'Edit Desa')
@section('page_title', 'Edit Data Desa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Profil Desa</h2>
            <p class="text-xs text-slate-500">Perbarui informasi profil dan data statistik desa</p>
        </div>
        <a href="{{ route('admin.villages.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.villages.update', $village->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Desa *</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $village->nama) }}" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="kepala_desa" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Kepala Desa</label>
                    <input type="text" name="kepala_desa" id="kepala_desa" value="{{ old('kepala_desa', $village->kepala_desa) }}"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="jumlah_penduduk" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jumlah Penduduk (Jiwa) *</label>
                    <input type="number" name="jumlah_penduduk" id="jumlah_penduduk" value="{{ old('jumlah_penduduk', $village->jumlah_penduduk) }}" min="0" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="luas_wilayah" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Luas Wilayah</label>
                    <input type="text" name="luas_wilayah" id="luas_wilayah" value="{{ old('luas_wilayah', $village->luas_wilayah) }}"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Saat Ini</label>
                <div class="mb-3">
                    <img src="{{ $village->foto_url }}" alt="{{ $village->nama }}" class="w-48 h-28 object-cover rounded-xl border border-slate-200 shadow-sm">
                </div>

                <label for="foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ganti Foto (Opsional)</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi & Potensi Desa</label>
                <textarea name="deskripsi" id="deskripsi" rows="5"
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('deskripsi', $village->deskripsi) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.villages.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Perbarui Data Desa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
