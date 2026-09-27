@extends('layouts.admin')

@section('title', 'Tambah Kategori Berkas')
@section('page_title', 'Tambah Kategori Berkas')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Kategori Berkas</h2>
            <p class="text-xs text-slate-500">Kelompokkan jenis dokumen unduhan publik</p>
        </div>
        <a href="{{ route('admin.download-categories.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.download-categories.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Kategori Berkas *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                       placeholder="Contoh: Formulir Pelayanan Kependudukan"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.download-categories.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
