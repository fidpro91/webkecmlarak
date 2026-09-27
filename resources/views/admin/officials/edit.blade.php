@extends('layouts.admin')

@section('title', 'Edit Pejabat')
@section('page_title', 'Edit Data Pejabat')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Data Pejabat</h2>
            <p class="text-xs text-slate-500">Perbarui nama, jabatan, urutan, atau foto pejabat</p>
        </div>
        <a href="{{ route('admin.officials.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.officials.update', $official->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap & Gelar *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $official->nama) }}" required
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label for="jabatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jabatan Kedinasan *</label>
                <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $official->jabatan) }}" required
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="urutan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Urutan *</label>
                    <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $official->urutan) }}" min="0" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    @if($official->foto)
                        <div class="mb-2 flex items-center gap-2">
                            <img src="{{ $official->foto_url }}" alt="{{ $official->nama }}" class="w-10 h-10 rounded-full object-cover">
                            <span class="text-xs text-slate-500">Foto saat ini</span>
                        </div>
                    @endif
                    <label for="foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.officials.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Perbarui Pejabat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
