@extends('layouts.admin')

@section('title', 'Tambah Pejabat')
@section('page_title', 'Tambah Pejabat / Aparatur Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Aparatur</h2>
            <p class="text-xs text-slate-500">Daftarkan pejabat baru dalam struktur hierarki kecamatan</p>
        </div>
        <a href="{{ route('admin.officials.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.officials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap & Gelar *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                       placeholder="Contoh: Drs. H. Bambang Sujarwo, M.Si"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label for="jabatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jabatan Kedinasan *</label>
                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" required
                       placeholder="Contoh: Camat Mlarak atau Kasi Tata Pemerintahan"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="urutan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Urutan *</label>
                    <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 1) }}" min="0" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    <p class="text-[11px] text-slate-400 mt-1">1 untuk Camat, 2 untuk Sekcam, dst.</p>
                </div>

                <div>
                    <label for="foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Pejabat (Pasfoto)</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.officials.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan Pejabat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
