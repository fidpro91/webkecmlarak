@extends('layouts.admin')

@section('title', 'Tambah Desa')
@section('page_title', 'Tambah Data Desa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Profil Desa</h2>
            <p class="text-xs text-slate-500">Daftarkan desa baru di wilayah administratif Kecamatan Mlarak</p>
        </div>
        <a href="{{ route('admin.villages.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.villages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Desa *</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                           placeholder="Contoh: Desa Gontor"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="kepala_desa" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Kepala Desa</label>
                    <input type="text" name="kepala_desa" id="kepala_desa" value="{{ old('kepala_desa') }}"
                           placeholder="Contoh: Drs. H. Ahmad Fauzi"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="jumlah_penduduk" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jumlah Penduduk (Jiwa) *</label>
                    <input type="number" name="jumlah_penduduk" id="jumlah_penduduk" value="{{ old('jumlah_penduduk', 0) }}" min="0" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="luas_wilayah" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Luas Wilayah</label>
                    <input type="text" name="luas_wilayah" id="luas_wilayah" value="{{ old('luas_wilayah') }}"
                           placeholder="Contoh: 3.80 km²"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label for="foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto / Pemandangan Desa</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi & Potensi Desa</label>
                <textarea name="deskripsi" id="deskripsi" rows="5" placeholder="Uraikan potensi pertanian, kerajinan, batas wilayah, fasilitas umum..."
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.villages.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan Data Desa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
